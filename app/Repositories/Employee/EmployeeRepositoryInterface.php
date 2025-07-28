<?php

namespace App\Repositories\Employee;

interface EmployeeRepositoryInterface
{
    public function all();
    public function create(array $employeeData);
    public function update(int $employeeId, array $employeeData);
    public function delete(int $employeeId);
}
