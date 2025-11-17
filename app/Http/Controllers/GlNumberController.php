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
        return $this->glNumberService->getCompletionGL($request->validated());
    }
}
