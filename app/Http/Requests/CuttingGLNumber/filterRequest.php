<?php

namespace App\Http\Requests\CuttingGLNumber;

use Illuminate\Foundation\Http\FormRequest;

class filterRequest extends FormRequest
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
            'gl_number' => 'required|string',
            'colors' => 'required|string',
        ];
    }

    public function validationData(): array
    {
        return $this->query->all();
    }
}
