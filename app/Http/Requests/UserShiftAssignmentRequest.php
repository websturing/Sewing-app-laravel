<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserShiftAssignmentRequest extends FormRequest
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
            'employee_selected_data' => 'required|array',
            'employee_selected_data.*' => 'unique:user_shift_assignments,user_id,NULL,id,effective_date_start,' . $this->effective_date,
            'shift_selected' => 'required|array', // atau custom rule untuk object
            'effective_date' => 'required|date_format:Y-m-d|after_or_equal:today',
        ];
    }
}
