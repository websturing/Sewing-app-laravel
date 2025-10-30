<?php

namespace App\Services\Glnumber;

use App\Repositories\Glnumber\GlnumberRepositoryInterface;
use App\Repositories\Stockin\StockinRepositoryInterface;
use App\Services\Stockin\StockinServiceInterface;

class GlnumberService implements GlnumberServiceInterface
{
    protected $glnumberRepository;
    protected $stockInService;
    protected $stockInRepository;

    public function __construct(
        GlnumberRepositoryInterface $glnumberRepository,
        StockinServiceInterface $stockInService,
        StockinRepositoryInterface $stockInRepository
    ) {
        $this->glnumberRepository = $glnumberRepository;
        $this->stockInService = $stockInService;
        $this->stockInRepository = $stockInRepository;
    }

    public function getAllGlnumber(array $filters)
    {
        return $this->stockInService->getByQuery($filters);
    }

    public function getPaginate(array $filters)
    {
        return $this->stockInService->getGroupByGlNumber("", 5, 'gl_no', 'ASC');
    }

    public function findGlNumber(string $glNumber)
    {
        return $this->glnumberRepository->findGlNumber($glNumber);
    }



    /**
     * Get grouped stock data by GL Number with pagination.
     *
     * @param string|null $searchTerm
     * @param int $perPage
     * @param string $sortBy
     * @param string $sortOrder
     * @return LengthAwarePaginator
     */

    public function glNumberByStockIns(
        ?string $searchTerm = null,
        int $perPage = 10,
        string $sortBy = 'total_pcs',
        string $sortOrder = 'desc'
    ) {
        return $this->stockInService->getGroupByGlNumber(
            $searchTerm,
            $perPage,
            $sortBy,
            $sortOrder
        );
    }
}
