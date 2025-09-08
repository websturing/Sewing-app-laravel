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

    public function getAllAssigmentline()
    {
        return $this->assigmentlineRepository->all();
    }
}
