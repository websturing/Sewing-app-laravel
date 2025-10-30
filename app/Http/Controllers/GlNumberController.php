<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentLineRequest;
use App\Http\Requests\GLnumberFilterRequest;
use App\Http\Resources\GlNumberResource;
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
}
