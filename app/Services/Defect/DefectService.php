<?php

namespace App\Services\Defect;

use App\Repositories\Defect\DefectRepositoryInterface;
use App\Services\Replacement\ReplacementServiceInterface;

class DefectService implements DefectServiceInterface
{
    protected $defectRepository;
    protected $replacementService;

    public function __construct(
        DefectRepositoryInterface $defectRepository,
        ReplacementServiceInterface $replacementService
    ) {
        $this->defectRepository = $defectRepository;
        $this->replacementService = $replacementService;
    }

    public function getAllDefect()
    {
        return $this->getDefectsGroupedByLine();
    }


    public function getSummaryGroupByLines()
    {

        $groupedLines = $this->getDefectsGroupedByLine();

        return [
            "total_lines"  => $groupedLines->count(),
            "total_defect" => $groupedLines->sum('total_defect'),
            "lines"        => $groupedLines
        ];
    }

    public function getDefectsGroupedByLine()
    {
        $groupLines =  $this->defectRepository->SummaryByGLLineSize()
            ->groupBy('line_name')
            ->map(function ($items, $line) {
                return [
                    'line_name' => $line,
                    'total_size' => count($items),
                    'total_defect' => $items->sum('total_defect'),
                    'total_pcs' => $items->sum('total_pcs'),
                    'items' => $items->values()
                ];
            })
            ->values();

        return $groupLines;
    }

    public function getGroupGlNumber()
    {

        $grouped = $this->defectRepository->SummaryByGLLineSize()
            ->groupBy('gl_no')
            ->map(function ($items, $glNo) {
                $replacement = $this->replacementService->getDefectByGLNumber($glNo)
                    ->groupBy('color')
                    ->map(function ($repItems) {
                        return $repItems->keyBy('size');  // akses cepat dengan ['color']['size']
                    });


                $groupByColor = $items->groupBy('color')->map(function ($item, $color) use ($replacement) {

                    $itemsAfterReplace = $item->map(function ($sizeItem) use ($replacement, $color) {
                        $rep = $replacement->get($color)?->get($sizeItem->size);


                        $sizeItem->replacement_pcs = $rep ? $rep->pcs : 0;
                        $sizeItem->total_defect = $sizeItem->total_defect - $sizeItem->replacement_pcs;
                        $sizeItem->balance_pcs     = $sizeItem->total_pcs - $sizeItem->replacement_pcs;

                        return $sizeItem;
                    });

                    return [
                        "color"        => $color,
                        "total_defect" => $item->sum('total_defect'),
                        "total_pcs"    => $itemsAfterReplace->sum('total_pcs'),
                        "total_replacement" => $itemsAfterReplace->sum('replacement_pcs'),
                        "balance_pcs"  => $itemsAfterReplace->sum('balance_pcs'),
                        "items"        => $itemsAfterReplace
                    ];
                });


                $totalDefect = $items->sum('total_defect');

                // Jika 0, return null untuk dibuang nanti
                if ($totalDefect == 0) {
                    return null;
                }


                return [
                    'gl_number'     => $glNo,
                    'color'         => $items->pluck('color')->unique()->implode(', '),
                    'total_size'    => count($items),
                    'total_defect'  => $totalDefect,
                    'total_pcs'     => $items->sum('total_pcs'),
                    'line_names'    => $items->pluck('line_name')->unique()->implode(', '),
                    'items'         => $groupByColor->values()
                ];
            })
            ->filter()         // hapus null result
            ->values();        // reindex array

        return $grouped;
    }
}
