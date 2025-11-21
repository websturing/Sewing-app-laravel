<?php

namespace App\Services\Leaders;

use App\Repositories\Leaders\LeadersRepositoryInterface;

class LeadersService implements LeadersServiceInterface
{
    protected $leadersRepository;

    public function __construct(LeadersRepositoryInterface $leadersRepository)
    {
        $this->leadersRepository = $leadersRepository;
    }

    public function getAllLeaders()
    {
        return $this->leadersRepository->all();
    }
}
