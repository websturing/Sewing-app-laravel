<?php

namespace App\Console\Commands;

use App\Models\CuttingGlColor;
use App\Models\CuttingGlColorSize;
use App\Models\CuttingGlSummary;
use DB;
use Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncCuttingSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-cutting-summary';
    protected $description = 'Sync GL summaries from Cutting (Sewing) API to local database';


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('SyncCuttingSummary executed at ' . now());
        $this->info('🚀 Starting Cutting Summary sync process...');

        // 1️⃣ Ambil GL Number unik dari table stock_ins
        $glNumbers = DB::table('stock_ins')
            ->select('gl_no')
            ->whereNotNull('gl_no')
            ->distinct()
            ->pluck('gl_no')
            ->toArray();

        if (empty($glNumbers)) {
            $this->warn('⚠️ No GL Numbers found in stock_ins.');
            Log::info('⚠️ No GL Numbers found in stock_ins at ' . now());
            return;
        }

        $this->info('Found ' . count($glNumbers) . ' unique GL numbers.');

        $url = rtrim(config('services.cutting.url'), '/') . '/summary-by-gl';
        $token = config('services.cutting.token');

        $bar = $this->output->createProgressBar(count($glNumbers));
        $bar->start();

        $successCount = 0;
        $failCount = 0;

        foreach ($glNumbers as $glNumber) {
            try {
                $response = Http::withToken($token)->get($url, [
                    'gl_number' => $glNumber
                ]);

                if (!$response->successful()) {
                    $failCount++;
                    $this->warn("❌ Failed to fetch GL: {$glNumber} (HTTP " . $response->status() . ")");
                    Log::info("❌ Failed to fetch GL: {$glNumber} (HTTP " . $response->status() . ")" . now());
                    continue;
                }

                $body = $response->json();
                $data = $body['data'] ?? null;

                if (!$data || !isset($data['gl_number'])) {
                    $failCount++;
                    $this->warn("⚠️ Invalid or empty data for GL: {$glNumber}");
                    Log::info("⚠️ Invalid or empty data for GL: {$glNumber}" . now());
                    continue;
                }

                DB::transaction(function () use ($data) {
                    $glNumber = $data['gl_number'];

                    // Ambil ringkasan GL utama (dari summary_by_gl[0])
                    $glInfo = $data['summary_by_gl'][0] ?? [];
                    $glSummary = $glInfo['gl_summary'] ?? [];

                    $glSummaryModel = CuttingGlSummary::updateOrCreate(
                        ['gl_number' => $glNumber],
                        [
                            'order_qty' => $glSummary['order_qty'] ?? 0,
                            'cut_qty' => $glSummary['cut_qty'] ?? 0,
                            'stock_out_qty' => $glSummary['stock_out_qty'] ?? 0,
                            'replacement_qty' => $glSummary['replacement_qty'] ?? 0,
                            'last_sync_at' => now(),
                        ]
                    );

                    // 2️⃣ Sinkronisasi warna per GL (summary_by_color)
                    foreach ($data['summary_by_color'] ?? [] as $colorData) {
                        $colorSummary = $colorData['summary'] ?? [];

                        $colorModel = CuttingGlColor::updateOrCreate(
                            [
                                'cutting_gl_summary_id' => $glSummaryModel->id,
                                'color' => $colorData['color'],
                            ],
                            [
                                'type' => $colorData['type'] ?? null,
                                'order_qty' => $colorSummary['order_qty'] ?? 0,
                                'cut_qty' => $colorSummary['cut_qty'] ?? 0,
                                'stock_out_qty' => $colorSummary['stock_out_qty'] ?? 0,
                                'replacement_qty' => $colorSummary['replacement_qty'] ?? 0,
                                'last_sync_at' => now(),
                            ]
                        );

                        // 3️⃣ Simpan size breakdown
                        foreach ($colorData['size_breakdown'] ?? [] as $size) {
                            CuttingGlColorSize::updateOrCreate(
                                [
                                    'cutting_gl_color_id' => $colorModel->id,
                                    'size' => $size['size'] ?? null,
                                ],
                                [
                                    'order_qty' => $size['order_qty'] ?? 0,
                                    'cut_qty' => $size['cut_qty'] ?? 0,
                                    'stock_out_qty' => $size['stock_out_qty'] ?? 0,
                                    'replacement_qty' => $size['replacement_qty'] ?? 0,
                                ]
                            );
                        }
                    }
                });

                $successCount++;
            } catch (\Throwable $e) {
                $failCount++;
                $this->error("💥 Exception for GL: {$glNumber} — " . $e->getMessage());
                Log::info("💥 Exception for GL: {$glNumber} — " . now());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Sync finished. Success: {$successCount}, Failed: {$failCount}");
        $this->info('Completed at ' . now());
        Log::info("✅ Sync finished. Success: {$successCount}, Failed: {$failCount}");
        Log::info('Completed at ' . now());
    }
}
