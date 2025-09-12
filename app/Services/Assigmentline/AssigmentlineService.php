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

    public function getAllAssigmentline(array $filters)
    {
        return $this->assigmentlineRepository->all($filters);
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
