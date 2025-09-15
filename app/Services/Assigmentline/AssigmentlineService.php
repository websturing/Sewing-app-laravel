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
        $items = $this->assigmentlineRepository->all($filters);

        return $items->map(function ($line) {
            $activeAssignments = $line->assignment->where('is_active', 1);

            $glNumbers = $activeAssignments
                ->map(fn($a) => $a->glnumber->gl_number ?? null)
                ->filter()
                ->unique()
                ->values();

            $colors = $activeAssignments
                ->flatMap(fn($a) => collect($a->layingPlanning)->pluck('color'))
                ->filter()
                ->unique()
                ->values();

            return [
                'id'         => $line->id,
                'name'       => $line->name,
                'created_at' => $line->created_at,
                'updated_at' => $line->updated_at,
                'location'   => $line->location,
                'glNumber'   => $glNumbers,
                'colors'     => $colors,
            ];
        });
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
