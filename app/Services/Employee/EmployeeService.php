<?php

namespace App\Services\Employee;

use App\Services\Employee\EmployeeServiceInterface;
use App\Repositories\Employee\EmployeeRepositoryInterface;

class EmployeeService implements EmployeeServiceInterface
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function getAllEmployee()
    {
        return $this->employeeRepository->all();
    }

    public function getEmployeeLastCode()
    {
        return $this->employeeRepository->lastEmployeeCode();
    }

    public function createEmployee(array $employeeData)
    {

        return $this->employeeRepository->create($employeeData);
    }

    public function updateEmployee(int $employeeId, array $employeeData)
    {

        return $this->employeeRepository->update($employeeId, $employeeData);
    }

    public function deleteEmployee(int $employeeId)
    {
        return $this->employeeRepository->delete($employeeId);
    }
}
