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

    public function getAllLine(array $filters)
    {
        return $this->lineRepository->all($filters)
            ->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 10);;
    }

    public function getLinePaginate(array $filters)
    {
        return $this->lineRepository->all($filters)
            ->orderBy('name', 'ASC')
            ->paginate($filters['per_page'] ?? 10);
    }
}
