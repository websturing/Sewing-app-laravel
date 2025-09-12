<?php

namespace App\Services\Assigmentline;

use App\Repositories\Assigmentline\AssigmentlineRepositoryInterface;

class AssigmentlineService implements AssigmentlineServiceInterface
{
    protected $assigmentlineRepository;

    public function __construct(AssigmentlineRepositoryInterface $assigmentlineRepository)
    {
        $this->assigmentlineRepository = $assigmentlineRepository;
    }

    public function getAll(array $filters)
    {
        return $items = $this->assigmentlineRepository->all($filters);

        return $items->filter(function ($item) use ($filters) {
            return str_contains(strtolower($item->line?->name ?? ''), strtolower($filters['line_name'] ?? '')) &&
                str_contains(strtolower($item->glnumber?->gl_number ?? ''), strtolower($filters['gl_number'] ?? '')) &&
                str_contains(strtolower($item->date_start ?? ''), strtolower($filters['date_start'] ?? '')) &&
                str_contains(strtolower($item->date_end ?? ''), strtolower($filters['date_end'] ?? ''));
        })->values();
    }

    public function create(array $createData)
    {
        return $this->assigmentlineRepository->create($createData);
    }
    public function update(int $id, array $updateData)
    {
        return $this->assigmentlineRepository->update($id, $updateData);
    }
    public function delete(int $id)
    {
        return $this->assigmentlineRepository->delete($id);
    }
}
