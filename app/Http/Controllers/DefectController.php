<?php

namespace App\Http\Controllers;

use App\Services\Defect\DefectServiceInterface;
use Illuminate\Http\Request;

class DefectController extends Controller
{

    public function __construct(
        private DefectServiceInterface $defectService,
    ) {}

    public function index()
    {
        return $this->defectService->getAllDefect();
    }

    public function summaryLines()
    {

        $results = $this->defectService->getSummaryGroupByLines();

        return response()->json([
            "status" => true,
            'message' => 'Data retrieved successfully',
            'data' => $results
        ], 200);
    }
}
