<?php

namespace App\Services\TestingGa;

use App\Repositories\TestingGa\TestingGaRepositoryInterface;

class TestingGaService implements TestingGaServiceInterface
{
    protected $testingGaRepository;

    public function __construct(TestingGaRepositoryInterface $testingGaRepository)
    {
        $this->testingGaRepository = $testingGaRepository;
    }

    public function getAllTestingGa()
    {
        return $this->testingGaRepository->all();
    }
}
