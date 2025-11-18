<?php

namespace App\Http\Controllers;

use App\DTOs\CuttingGLNumber\FilterDTO;
use App\Http\Requests\AssignmentLineRequest;
use App\Http\Requests\CompletionReportGlRequest;
use App\Http\Requests\CuttingGLNumber\filterRequest;
use App\Http\Requests\GLnumberFilterRequest;
use App\Http\Requests\GLNumberMatrixDateRequest;
use App\Http\Requests\GLnumberSyncCuttingSewingFilterRequest;
use App\Http\Resources\GLNumberMatrixResource;
use App\Http\Resources\GlNumberResource;
use App\Http\Resources\GLNumberSyncCuttingResource;
use App\Models\GlNumber;
use App\Services\Cutting\CuttingIntegrationServiceInterface;
use App\Services\Glnumber\GlnumberServiceInterface;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GlNumberController extends Controller
{
    public function __construct(
        private GlnumberServiceInterface $glNumberService,
        private CuttingIntegrationServiceInterface $cuttingIntegrationService,
    ) {}

    public function index(GLnumberFilterRequest $filters)
    {

        $GlNumbers = $this->glNumberService
            ->glNumberByStockIns(
                $searchTerm = $filters['search'],
                $perPage = $filters['per_page'] ?? 10,
                $sortBy = 'gl_no',
                $sortOrder = 'ASC',
                $page = $filters['page'] ?? 1
            );

        if (!$GlNumbers) {
            return errorResponse('List Line Not Found', 404);
        }

        return $collection = GlNumberResource::collection($GlNumbers)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved GL Numbers',
        ]);
    }



    public function show(string $glNumber)
    {
        $glNumberData = $this->glNumberService->findGlNumber($glNumber);

        if (!$glNumberData) {
            return errorResponse('GL Number Not Found', 404);
        }

        return successResponse(new GlNumberResource($glNumberData), 'Succesfully Retrieved GL Number');
    }

    public function cuttingSummary(Request $request)
    {

        return $summary = $this->cuttingIntegrationService->summaryGlNumber($request->all());

        if (!$summary) {
            return errorResponse('Cutting Summary Not Found', 404);
        }

        return successResponse('Succesfully Retrieved Cutting Summary', $summary);
    }

    public function matrixDate(GLNumberMatrixDateRequest $request)
    {
        $data = $request->validated();

        $results = $this->glNumberService->getMatrixDate(
            $data['gl_number'],
            $data['start_date'] ?? null,
            $data['end_date'] ?? null
        );

        return GLNumberMatrixResource::make($results)->additional([
            "status" => true,
            'message' => 'Succesfully Retrieved Data'
        ]);
    }


    /**
     * SYNC GL NUMBER CUTTING & SEWING
     */

    public function syncCuttingAndSewingSummaries(filterRequest $request)
    {

        $filters['gl_number'] =   $request['gl_number'] ?? null;
        $filters['colors'] =   $request['colors'] ?? null;

        $combineData = $this->glNumberService->syncCuttingAndSewingSummaries($filters);

        return GLNumberSyncCuttingResource::make($combineData)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved Data'
        ]);
    }

    public function getList(Request $request)
    {
        $glNumber = $request->get('gl_number') ?? null;
        $results = $this->glNumberService->glNumberWithColor($glNumber);

        return response()->json([
            'status' => true,
            'message' => 'Successfully Retrived Data',
            'data' => $results
        ]);
    }

    public function getCompletionByGLNumber(CompletionReportGlRequest $request)
    {
        return $this->glNumberService->getCompletionGL($request->validated());
    }

    public function pdfCompletionReport(CompletionReportGlRequest $request)
    {
        $filters = $request->validated();
        $glNumber = $filters['gl_number'] ?? '-';


        $results = $this->glNumberService->getCompletionGL($filters);
        $title = 'Completion Report GL-' . $glNumber;
        $startDate = $filters['start_date'] ?? $results['first_updated_at'];
        $endDate = $filters['end_date'] ?? $results['last_updated_at'];

        $diffStockIn = $results['total_pcs'] - $results['mi_order'] >  0 ? '+' : '' . $results['total_pcs'] - $results['mi_order'];
        $diffStockOutput = $results['total_output'] - $results['mi_order'] >  0 ? '+' : '' . $results['total_output'] - $results['mi_order'];
        $pdf = Pdf::loadView('completionReportGLPDF', compact(
            'title',
            'startDate',
            'endDate',
            'glNumber',
            'results',
            'diffStockIn',
            'diffStockOutput'

        ))
            ->setPaper('A4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true);

        // 2️⃣ Ambil DomPDF instance dan render dulu sebelum kasih nomor halaman
        $dompdf = $pdf->getDomPDF();
        $dompdf->render();

        // 3️⃣ Tambahkan teks halaman di tengah bawah
        $canvas = $dompdf->getCanvas();
        $w = $canvas->get_width();
        $h = $canvas->get_height();

        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $font = $dompdf->getFontMetrics()->get_font("helvetica", "normal");
        $size = 9;
        $textWidth = $dompdf->getFontMetrics()->getTextWidth($text, $font, $size);
        $x = ($w - $textWidth) + 80;
        $y = $h - 25;

        $canvas->page_text($x, $y, $text, $font, $size, [0, 0, 0]);

        // 4️⃣ Stream hasil
        return $pdf->stream('completion_report_' . $glNumber . '.pdf');
    }
}
