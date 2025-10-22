<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockInSummaryGroupGlNumberResource;
use App\Http\Resources\StockInSummaryResource;
use Illuminate\Http\Request;
use App\Services\Stockin\StockinServiceInterface;
use App\Services\Stockin\StockInSummaryServiceInterface;
use Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class StockInSummaryController extends Controller
{
    public function __construct(
        private StockinServiceInterface $stockInService,
        private StockInSummaryServiceInterface $stockInSummaryService,
    ) {}

    public function summary(Request $request)
    {


        $filters = $request->get('filters');
        $filters['start_date'] = $filters['start_date'] ?? Carbon::today()->format('Y-m-d');
        $filters['end_date'] = $filters['end_date'] ?? Carbon::today()->format('Y-m-d');

        $items =  $this->stockInService->summary($filters);

        return StockInSummaryResource::make($items)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved Stock-in Summary'
        ]);
    }

    public function stockInChart(Request $request)
    {
        $filters = $request->get('filters');
        $filters['start_date'] = $filters['start_date'] ?? Carbon::today()->format('Y-m-d');
        $filters['end_date'] = $filters['end_date'] ?? Carbon::today()->format('Y-m-d');

        $charts = $this->stockInSummaryService->chart($filters);

        return response()->json([
            'status' => true,
            'message' => 'Succesfully Retrieved Stock-in Chart Data',
            'data' => $charts
        ]);
    }

    /**
     * STOCK IN BY GL NUMBER
     */
    public function stockInByGlNumber(Request $request)
    {

        $results = $this->stockInSummaryService->groupByGlNumber($request->get('search', ''));



        return StockInSummaryGroupGlNumberResource::collection($results)->additional([
            'status' => true,
            'message' => 'Successfully Retrieved Stock-in by GL Number'
        ]);
    }
    public function stockInByGlNumberColor(Request $request)
    {

        return $results = $this->stockInSummaryService->groupByGlNumberColor($request->get('search', ''));
    }

    public function pdf(Request $request)
    {
        $title = "Stock In Summary";
        $user = "Admin";

        $rows = $this->stockInSummaryService->groupByGlNumberColor($request->get('search', ''));

        // 1️⃣ Load view
        $pdf = Pdf::loadView('stock-ins.pdf.summaryStockIn', compact('user', 'title', 'rows'))
            ->setPaper('A4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true);

        // 2️⃣ Ambil DomPDF instance dan render dulu sebelum kasih nomor halaman
        $dompdf = $pdf->getDomPDF();
        $dompdf->render();

        // 3️⃣ Tambahkan teks halaman di tengah bawah
        $canvas = $dompdf->getCanvas();
        $w = $canvas->get_width();
        $h = $canvas->get_height();

        $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
        $font = $dompdf->getFontMetrics()->get_font("helvetica", "normal");
        $size = 9;
        $textWidth = $dompdf->getFontMetrics()->getTextWidth($text, $font, $size);
        $x = ($w - $textWidth) + 80;
        $y = $h - 25;

        $canvas->page_text($x, $y, $text, $font, $size, [0, 0, 0]);

        // 4️⃣ Stream hasil
        return $pdf->stream('StockInSummary.pdf');
    }
}
