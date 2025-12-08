<?php

namespace App\Services\Glnumber;

interface GlnumberServiceInterface
{
    public function getAllGlnumber(array $filters);
    public function getPaginate(array $filters);



    /**
     * Get Matrix grouped stock data by GL Number .
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string $glNumber
     * @return LengthAwarePaginator
     */

    public function getMatrixDate(
        $glNumber,
        $startDate = null,
        $endDate = null
    );


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
    );

    public function syncCuttingAndSewingSummaries(array $filters);
    public function findGlNumber(string $glNumber);
}
