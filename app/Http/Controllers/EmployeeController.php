<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Services\Employee\EmployeeServiceInterface;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(
        private EmployeeServiceInterface $employeeService
    ) {}

    function index()
    {
        $shift = $this->employeeService->getAllEmployee();

        if (!$shift) {
            return errorResponse('Shift not found', 404);
        }

        $collection = EmployeeResource::collection($shift);
        return successResponse($collection);
    }
}
