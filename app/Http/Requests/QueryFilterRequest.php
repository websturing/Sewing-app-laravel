<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueryFilterRequest extends FormRequest
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
            'filters' => 'nullable|array',
            'sorts' => 'nullable|array',
            'sorts.*' => 'in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'with_relations' => 'nullable|boolean',
            'relations' => 'nullable|array'
        ];
    }

    public function getFilters(): ?array
    {
        return $this->input('filters');
    }

    public function getSorts(): array
    {
        return $this->input('sorts', ['created_at' => 'desc']);
    }

    public function getPerPage(): int
    {
        return $this->input('per_page', 15);
    }

    public function getPage(): int
    {
        return $this->input('page', 1);
    }

    public function getWithRelations(): bool
    {
        return $this->input('with_relations', true);
    }

    public function getRelations(): array
    {
        return $this->input('relations', []);
    }
}
