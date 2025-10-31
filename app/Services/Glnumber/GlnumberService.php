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

        $glnumber = [
            'gl_no' => $filters['gl_number']
        ];

        $colorStockIns =  $this->stockInService->getGroupBySizeAndColorBy($glnumber);
        $filters = FilterDTO::fromArray($filters);

        $CuttingGLNumber = $this->cuttingGlnumberService->findBy($filters);



        $colorStockIns = collect($colorStockIns);
        $colorStockOuts = collect($colorStockOuts ?? []); // <— aman walau [] atau null

        $cuttingData = $CuttingGLNumber['data'][0]['colors'] ?? [];

        foreach ($cuttingData as &$color) {
            $totalStockIn = 0;
            $totalStockOut = 0;

            foreach ($color['sizes'] as &$size) {
                // cari stock in
                $foundIn = $colorStockIns->first(function ($stock) use ($color, $size) {
                    return strtolower(trim($stock['color'])) === strtolower(trim($color['color']))
                        && strtolower(trim($stock['size'])) === strtolower(trim($size['size']));
                });

                // cari stock out — tetap aman walau $colorStockOuts kosong
                $foundOut = $colorStockOuts->first(function ($stock) use ($color, $size) {
                    return strtolower(trim($stock['color'])) === strtolower(trim($color['color']))
                        && strtolower(trim($stock['size'])) === strtolower(trim($size['size']));
                });

                $size['sewing_stockin_qty'] = isset($foundIn['total_qty']) ? (int)$foundIn['total_qty'] : 0;
                $size['sewing_stockout_qty'] = isset($foundOut['total_qty']) ? (int)$foundOut['total_qty'] : 0;

                $totalStockIn += $size['sewing_stockin_qty'];
                $totalStockOut += $size['sewing_stockout_qty'];
            }

            $color['sewing_total_stockin_qty'] = $totalStockIn;
            $color['sewing_total_stockout_qty'] = $totalStockOut;
        }

        // Total keseluruhan (opsional)
        $totalAllStockIns = array_sum(array_column($cuttingData->toArray(), 'sewing_total_stockin_qty'));
        $totalAllStockOuts = array_sum(array_column($cuttingData->toArray(), 'sewing_total_stockout_qty'));


        $CuttingGLNumber['data'][0]['sewing_total_stockin_qty'] = $totalAllStockIns;
        $CuttingGLNumber['data'][0]['sewing_total_stockout_qty'] = $totalAllStockOuts;
        $CuttingGLNumber['data'][0]['colors'] = $cuttingData;



        return $CuttingGLNumber;
    }
}
