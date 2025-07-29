<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\Employee\EmployeeServiceInterface;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(
        private EmployeeServiceInterface $employeeService
    ) {}

    function index()
    {
        $employee = $this->employeeService->getAllEmployee();

        if (!$employee) {
            return errorResponse('Shift not found', 404);
        }

        $collection = EmployeeResource::collection($employee);
        return successResponse($collection);
    }

    function getEmployeeLastCode()
    {
        $code = $this->employeeService->getEmployeeLastCode();
        return successResponse($code->employee_code, 'Successfully Retrived Last Code Employee');
    }

    function createEmployee(EmployeeRequest $request)
    {
        $employee = new EmployeeResource($this->employeeService->createEmployee($request->validated()));
        return successResponse($employee, 'Succesfully Created Employee : ' . $employee->name);
    }

    function updateEmployee(EmployeeRequest $request, $employeeId)
    {
        $employee = new EmployeeResource($this->employeeService->updateEmployee($employeeId, $request->validated()));
        return successResponse($employee, 'Succesfully Updated Employee : ' . $employee->name);
    }

    function deleteEmployee($employeeId)
    {
        $deletedEmployee = $this->employeeService->deleteEmployee($employeeId);
        if (!$deletedEmployee) {
            return errorResponse('Shift not found', 404);
        }

        return successResponse(
            new EmployeeResource($deletedEmployee),
            "Successfully deleted shift: {$deletedEmployee->name}"
        );
    }
}
