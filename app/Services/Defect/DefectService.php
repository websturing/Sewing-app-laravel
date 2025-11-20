<?php

namespace App\Services\Defect;

use App\Repositories\Defect\DefectRepositoryInterface;

class DefectService implements DefectServiceInterface
{
    protected $defectRepository;

    public function __construct(DefectRepositoryInterface $defectRepository)
    {
        $this->defectRepository = $defectRepository;
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
                    'items' => $items->values()
                ];
            })
            ->values();

        return $groupLines;
    }
}
