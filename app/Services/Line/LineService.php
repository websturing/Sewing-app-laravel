<?php

namespace App\Services\Line;

use App\Repositories\Line\LineRepositoryInterface;

class LineService implements LineServiceInterface
{
    protected $lineRepository;

    public function __construct(LineRepositoryInterface $lineRepository)
    {
        $this->lineRepository = $lineRepository;
    }

    public function getAllLine()
    {
        return $this->lineRepository->all();
    }
}
