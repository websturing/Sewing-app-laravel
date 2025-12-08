<?php

namespace App\Services\Glnumber;

use App\DTOs\CuttingGLNumber\FilterDTO;
use App\Repositories\Glnumber\GlnumberRepositoryInterface;
use App\Repositories\Stockin\StockinRepositoryInterface;
use App\Services\Cutting\CuttingIntegrationService;
use App\Services\CuttingGlnumber\CuttingGlnumberServiceInterface;
use App\Services\Stockin\StockInGroupService;
use App\Services\Stockin\StockInGroupServiceInterface;
use App\Services\Stockin\StockinServiceInterface;

class GlnumberService implements GlnumberServiceInterface
{
    protected $glnumberRepository;
    protected $stockInService;
    protected $stockInGroupService;
    protected $stockInRepository;
    protected $cuttingIntegration;
    protected $cuttingGlnumberService;

    public function __construct(
        GlnumberRepositoryInterface $glnumberRepository,
        StockinServiceInterface $stockInService,
        StockInGroupServiceInterface $stockInGroupService,
        CuttingGlnumberServiceInterface $cuttingGlnumberService,
        CuttingIntegrationService  $cuttingIntegration,
    ) {
        $this->glnumberRepository = $glnumberRepository;
        $this->stockInService = $stockInService;
        $this->stockInGroupService = $stockInGroupService;
        $this->cuttingGlnumberService = $cuttingGlnumberService;
        $this->cuttingIntegration = $cuttingIntegration;
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
     * Get Matrix grouped stock data by GL Number .
     *
     * @param string|null $startDate
     * @param string $endDate
     * @param string $glNumber
     * @return LengthAwarePaginator
     */

    public function getMatrixDate(
        $glNumber,
        $startDate = null,
        $endDate = null
    ) {
        return $this->stockInGroupService->getMatrixDate($glNumber, $startDate, $endDate);
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



        return $CuttingGLNumber['data'][0];
    }

    public function glNumberWithColor(?string $glNumber)
    {
        return $this->glnumberRepository->glNumberWithColor($glNumber);
    }

    public function getCompletionGL(array $filters)
    {
        if ($filters['color'] == 'all') {
            return $this->getCompletionGLColorAll($filters);
        } else {
            return $this->getCompletionGLSelecetdColor($filters);
        }
    }

    public function getCompletionGLSelecetdColor(array $filters)
    {
        $gl = $filters['gl_number'];
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        $cuttingApi = $this->cuttingIntegration->summaryGlNumber([
            'gl_number' => $gl
        ]);
        $layingPlannings = collect($cuttingApi['data']['summary_by_gl'][0]['laying_plannings'] ?? []);
        $cuttingApiGrandTotal = collect($cuttingApi['data']['grand_total']);

        $sewingRecords = $this->glnumberRepository->getGlNumberGroup($filters);

        $cuttingLookup = collect($layingPlannings)
            ->mapWithKeys(function ($item) {

                $sizes = collect($item['size_breakdown'])
                    ->mapWithKeys(function ($size) {
                        return [
                            $size['size'] => [
                                'order_qty'       => (int)$size['order_qty'],
                                'cut_qty'         => $size['cut_qty'],
                                'stock_out_qty'   => $size['stock_out_qty'],
                                'replacement_qty' => $size['replacement_qty'],
                            ]
                        ];
                    });

                return [
                    $item['color'] => [
                        'sizes'   => $sizes,
                        'summary' => [
                            'order_qty'       => $item['summary']['order_qty'],
                            'cut_qty'         => $item['summary']['cut_qty'],
                            'stock_out_qty'   => $item['summary']['stock_out_qty'],
                            'replacement_qty' => $item['summary']['replacement_qty'],
                        ]
                    ]
                ];
            });

        return $sewingRecords
            ->groupBy('gl_no')
            ->map(function ($groupedByGl) use ($startDate, $endDate, $cuttingLookup, $cuttingApiGrandTotal) {

                $colors = $groupedByGl
                    ->groupBy('color')
                    ->map(function ($byColor) use ($startDate, $endDate, $cuttingLookup) {

                        $sizes = $byColor->map(function ($r) use ($cuttingLookup) {

                            // Cutting data lookup
                            $cutting = $cuttingLookup[$r->color]['sizes'][$r->size] ?? [
                                'order_qty'       => 0,
                                'cut_qty'         => 0,
                                'stock_out_qty'   => 0,
                                'replacement_qty' => 0,
                            ];

                            return [
                                'size'   => $r->size,
                                'bundle' => $r->total_bundle,
                                'pcs'    => $r->total_pcs,
                                'defect' => $r->total_defect,

                                // Inject cutting data
                                'order_qty'       => $cutting['order_qty'],
                                'cut_qty'         => $cutting['cut_qty'],
                                'stock_out_qty'   => $cutting['stock_out_qty'],
                                'replacement_qty' => $cutting['replacement_qty'],
                            ];
                        })->values();

                        return [
                            'color' => $byColor->first()->color,
                            'total_bundle' => $byColor->sum('total_bundle'),
                            'total_pcs' => $byColor->sum('total_pcs'),
                            'total_defect' => $byColor->sum('total_defect'),
                            'total_order_qty' => $sizes->sum('order_qty'),
                            'mi_order' => $cuttingLookup[$byColor->first()->color]['summary']['order_qty'] ?? 0,
                            'first_updated_at' => $startDate ?? $byColor->min('start_updated_at'),
                            'last_updated_at'  => $endDate ?? $byColor->max('updated_at'),

                            // updated sizes array
                            'sizes' => $sizes
                        ];
                    })
                    ->values();

                $globalFirst = $colors->min('first_updated_at');
                $globalLast  = $colors->max('last_updated_at');

                return [
                    'gl_no' => $groupedByGl->first()->gl_no,
                    'total_colors' => $groupedByGl->groupBy('color')->count(),
                    'total_pcs' => $groupedByGl->sum('total_pcs'),
                    'total_output' => 0,
                    'mi_order' => $colors->sum('mi_order'),
                    'first_updated_at' => $globalFirst ? \Carbon\Carbon::parse($globalFirst)->format('Y-m-d') : null,
                    'last_updated_at'  => $globalLast  ? \Carbon\Carbon::parse($globalLast)->format('Y-m-d')  : null,
                    'colors' => $colors,
                ];
            })
            ->first();

        return $cutting;
    }


    public function getCompletionGLColorAll(array $filters)
    {
        $gl = $filters['gl_number'];

        // ---------------------------
        // 1) Ambil Cutting
        // ---------------------------
        $cuttingApi = $this->cuttingIntegration->summaryGlNumber([
            'gl_number' => $gl
        ]);

        $cutting = collect($cuttingApi['data']['summary_by_gl'][0]['laying_plannings'] ?? []);
        $cuttingApiGrandTotal = collect($cuttingApi['data']['grand_total']);
        // ---------------------------
        // 2) Ambil Sewing Lookup
        // ---------------------------
        $sewing = $this->glnumberRepository->getGlNumberGroup($filters);

        $sewingLookup = [];
        foreach ($sewing as $r) {
            $sewingLookup[$r->color][$r->size] = [
                'bundle' => (int) $r->total_bundle,
                'pcs'    => (int) $r->total_pcs,
                'defect' => (int) $r->total_defect,
                'first_updated_at' => $r->start_updated_at,
                'last_updated_at' => $r->updated_at,
            ];
        }

        // ---------------------------
        // 3) Map Cutting → per color → per size
        // ---------------------------
        $colors = $cutting
            ->groupBy('color')
            ->map(function ($items, $color) use ($sewingLookup) {

                $sizes = collect();

                foreach ($items as $item) {
                    foreach ($item['size_breakdown'] as $sb) {

                        $size = $sb['size'] ?? null;

                        // Sewing data
                        $sewing = $sewingLookup[$color][$size] ?? [
                            'bundle' => 0,
                            'pcs'    => 0,
                            'defect' => 0,
                            'first_updated_at' => null,
                            'last_updated_at' => null,
                        ];

                        // Push size
                        $sizes->push([
                            'size' => $size,

                            // Sewing
                            'bundle' => $sewing['bundle'],
                            'pcs'    => $sewing['pcs'],
                            'defect' => $sewing['defect'],

                            'first_updated_at' => $sewing['first_updated_at'],
                            'last_updated_at'  => $sewing['last_updated_at'],

                            // Cutting
                            'order_qty'       => (int) ($sb['order_qty'] ?? 0),
                            'cut_qty'         => (int) ($sb['cut_qty'] ?? 0),
                            'stock_out_qty'   => (int) ($sb['stock_out_qty'] ?? 0),
                            'replacement_qty' => (int) ($sb['replacement_qty'] ?? 0),
                        ]);
                    }
                }



                return [
                    'color' => $color,
                    // Sewing total
                    'total_bundle' => $sizes->sum('bundle'),
                    'total_pcs'    => $sizes->sum('pcs'),
                    'total_defect' => $sizes->sum('defect'),
                    // Cutting totals
                    'total_order_qty' => $sizes->sum('order_qty'),
                    'first_updated_at' => $sizes->min('first_updated_at'),
                    'last_updated_at'  => $sizes->max('last_updated_at'),
                    'sizes' => $sizes->values(),
                ];
            })
            ->values();

        $globalFirst = $colors->min('first_updated_at');
        $globalLast = $colors->max('last_updated_at');

        // ---------------------------
        // 4) Return GL level
        // ---------------------------
        return [
            'gl_no' => $gl,
            'total_colors' => $colors->count(),
            'total_pcs' => $colors->sum('total_pcs'),
            'total_output' => 0,
            'mi_order' => $cuttingApiGrandTotal['order_qty'],
            'first_updated_at' => $globalFirst,
            'last_updated_at' => $globalLast ? \Carbon\Carbon::parse($globalLast)->format('Y-m-d') : null,
            'colors' => $colors,
        ];
    }
}
