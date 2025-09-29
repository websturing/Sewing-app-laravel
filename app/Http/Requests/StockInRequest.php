<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockInRequest extends FormRequest
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
            'id' => 'nullable|integer',
            'serial_number' => 'required|string|max:191',
            'ticket_no' => 'required|integer',
            'gl_no' => 'required|string|max:191',
            'size' => 'required|string|max:10',
            'user_dispatch_id' => 'nullable|integer',
            'color' => 'required|string|max:255',
            'pcs' => 'required|integer',
            'date_stock_out' => 'required|date',
            'cor_id' => 'required|integer',
            'user_id' => 'required|integer',
            'user_dispatch_name' => 'nullable|string|max:191',
            'box_number' => 'nullable|string|max:191',
            'line_id' => 'required|integer',
            'input_source' => 'nullable|string|max:255',
            'container_scan_status' => 'nullable|string|max:255'
        ];
    }
}
