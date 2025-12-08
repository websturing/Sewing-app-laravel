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
        return $this->defectRepository->groupLines();
    }
}
