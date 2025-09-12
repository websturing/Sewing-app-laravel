<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignmentLineRequest extends FormRequest
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
            'gl_id' => 'required|integer',
            'line_id' => 'required|integer',
            'date_start' => 'required|date_format:Y-m-d',
            'date_end' => 'required|date_format:Y-m-d',
            'laying_planning' => 'nullable|array',
            'laying_planning.color' => [
                'required',
                Rule::unique('laying_plannings', 'color')
                    ->where('assignment_line_id', $this->assignment_line_id)
                    ->where('type', $this->laying_planning['type'] ?? null)
            ],
            'laying_planning.type' => 'required_with:laying_planning|string',
            'laying_planning.summary.order_qty' => 'required_with:laying_planning|integer',
            'laying_planning.summary.cut_qty' => 'required_with:laying_planning|integer',
        ];
    }
}
