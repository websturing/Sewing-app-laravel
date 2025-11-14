<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncGlNumbers extends Command
{
    protected $signature = 'sync:glnumbers';
    protected $description = 'Sync unique GL numbers from stock_ins to gls table';

    public function handle()
    {
        // Step 1: Ambil unique gl_no dari stock_ins
        $glNumbers = DB::table('stock_ins')
            ->select('gl_no')
            ->distinct()
            ->pluck('gl_no')
            ->toArray();

        // Step 2: Ambil gl_number yang sudah ada di tabel gls
        $existing = DB::table('gls')
            ->pluck('gl_number')
            ->toArray();

        // Step 3: Filter yang belum ada
        $newGl = array_diff($glNumbers, $existing);

        // Step 4: Insert batch
        foreach ($newGl as $gl) {
            DB::table('gls')->insert([
                'gl_number' => $gl,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->info("Synced GL Numbers. Inserted: " . count($newGl));

        return Command::SUCCESS;
    }
}
