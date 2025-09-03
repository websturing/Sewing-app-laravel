<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'date_birth' => 'required|date_format:Y-m-d|max:255',
            'employee_code' => 'required|string|max:255',
            'position' => 'required|string',
            'department' => 'required|string',
            'join_date' => 'required|date_format:Y-m-d',
            'active' => 'required|boolean',
            'user_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
