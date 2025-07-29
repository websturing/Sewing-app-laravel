<?php

namespace App\Repositories\Employee;

use App\Models\Employee;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function all()
    {
        return Employee::with(['user'])->get();
    }

    public function lastEmployeeCode()
    {
        return Employee::orderBy('employee_code', 'DESC')->first();
    }

    public function create(array $employeeData)
    {
        return Employee::create($employeeData);
    }

    public function update(int $employeeId, array $employeeData)
    {
        $employee = Employee::findOrFail($employeeId);
        $employee->update($employeeData);
        return $employee;
    }


    public function delete(int $employeeId)
    {
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return null;
        }

        // Simpan data shift sebelum dihapus
        $deletedemployee = clone $employee;

        // Hapus shift
        $employee->delete();

        // Kembalikan data yang sudah dihapus
        return $deletedemployee;
    }
}
