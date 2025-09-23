<?php

namespace App\Services\Stockin;

use App\Repositories\Stockin\StockinRepositoryInterface;

class StockinService implements StockinServiceInterface
{
    protected $stockinRepository;

    public function __construct(StockinRepositoryInterface $stockinRepository)
    {
        $this->stockinRepository = $stockinRepository;
    }

    public function getAllStockin()
    {
        return $this->stockinRepository->all();
    }

    public function create(array $data)
    {
        return $this->stockinRepository->create($data);
    }

    public function getPaginate(array $filters)
    {
        return $this->stockinRepository->paginateAll($filters)
            ->with('line')
            ->orderBy('created_at', 'DESC')
            ->paginate($filters['per_page'] ?? 100);
    }
}
