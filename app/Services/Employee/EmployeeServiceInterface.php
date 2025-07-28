<?php

namespace App\Services\Employee;

interface EmployeeServiceInterface
{
    public function getAllEmployee();
    public function createEmployee(array $employeeData);
    public function updateEmployee(int $employeeId, array $employeeData);
    public function deleteEmployee(int $employeeId);
}
