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



    public function getAllEmployee(array $filters)
    {
        $query = $this->employeeRepository->query();

        if (!empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(
                fn($q) =>
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
            );
        }

        return $query->orderBy('created_at', 'desc')->get();
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

    public function paginateEmployee(array $filters)
    {
        $query = $this->employeeRepository->query();

        if (!empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(
                fn($q) =>
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
            );
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 10);
    }
}
