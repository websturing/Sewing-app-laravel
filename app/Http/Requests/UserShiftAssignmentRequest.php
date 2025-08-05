<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

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
            'employee_selected_data.*.user.id' => [
                'required',
                'distinct',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    $exists = \DB::table('user_shift_assignments')
                        ->where('user_id', $value)
                        ->whereDate('effective_date_start', $this->effective_date)
                        ->exists();

                    if ($exists) {
                        $fail("User {$user->name} already has a shift assignment on {$this->effective_date}");
                    }
                }
            ],
            'shift_selected' => 'required|array',
            'effective_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
        ];
    }
}
