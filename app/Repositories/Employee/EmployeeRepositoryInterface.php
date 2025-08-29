<?php

namespace App\Repositories\Employee;

use Illuminate\Database\Eloquent\Builder;

interface EmployeeRepositoryInterface
{
    public function query(): Builder;
    public function all();
    public function lastEmployeeCode();
    public function create(array $employeeData);
    public function update(int $employeeId, array $employeeData);
    public function delete(int $employeeId);
}
