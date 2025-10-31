<?php

namespace App\Services\Glnumber;

use App\DTOs\CuttingGLNumber\FilterDTO;
use App\Repositories\Glnumber\GlnumberRepositoryInterface;
use App\Repositories\Stockin\StockinRepositoryInterface;
use App\Services\Cutting\CuttingIntegrationService;
use App\Services\CuttingGlnumber\CuttingGlnumberServiceInterface;
use App\Services\Stockin\StockinServiceInterface;

class GlnumberService implements GlnumberServiceInterface
{
    protected $glnumberRepository;
    protected $stockInService;
    protected $stockInRepository;
    protected $cuttingIntegration;
    protected $cuttingGlnumberService;

    public function __construct(
        GlnumberRepositoryInterface $glnumberRepository,
        StockinServiceInterface $stockInService,
        CuttingGlnumberServiceInterface $cuttingGlnumberService
    ) {
        $this->glnumberRepository = $glnumberRepository;
        $this->stockInService = $stockInService;
        $this->cuttingGlnumberService = $cuttingGlnumberService;
    }

    public function getAllGlnumber(array $filters)
    {
        return $this->stockInService->getByQuery($filters);
    }

    public function getPaginate(array $filters)
    {
        return $this->stockInService->getGroupByGlNumber("", 5, 'gl_no', 'ASC', 1);
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
        string $sortOrder = 'desc',
        int $page = 1
    ) {
        return $this->stockInService->getGroupByGlNumber(
            $searchTerm,
            $perPage,
            $sortBy,
            $sortOrder,
            $page
        );
    }


    public function syncCuttingAndSewingSummaries($filters)
    {



        $filters = FilterDTO::fromArray($filters);

        $stockInsGroupByGL = $this->cuttingGlnumberService->findBy($filters);




        return $stockInsGroupByGL;
    }
}
