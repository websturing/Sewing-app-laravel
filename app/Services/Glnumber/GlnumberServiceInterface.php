<?php

namespace App\Services\Glnumber;

interface GlnumberServiceInterface
{
    public function getAllGlnumber(array $filters);
    public function getPaginate(array $filters);



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


    public function findGlNumber(string $glNumber);
}
