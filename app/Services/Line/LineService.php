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
            ->orderByRaw('CAST(SUBSTRING(name, 6) AS UNSIGNED) ASC');
    }

    public function getLinePaginate(array $filters)
    {
        return $this->lineRepository->all($filters)
            ->orderByRaw('CAST(SUBSTRING(name, 6) AS UNSIGNED) ASC')
            ->paginate($filters['per_page'] ?? 100);
    }
}
