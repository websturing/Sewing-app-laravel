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

    public function getDefectsGroupedByLine()
    {
        return $this->defectRepository->SummaryByGLLineSize()
            ->groupBy('line_name')
            ->map(function ($items, $line) {
                return [
                    'line_name' => $line,
                    'items' => $items->values()
                ];
            })
            ->values();
    }
}
