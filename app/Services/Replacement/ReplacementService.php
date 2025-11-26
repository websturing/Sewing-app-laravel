<?php

namespace App\Services\Replacement;

use App\Repositories\Replacement\ReplacementRepositoryInterface;

class ReplacementService implements ReplacementServiceInterface
{
    protected $replacementRepository;

    public function __construct(ReplacementRepositoryInterface $replacementRepository)
    {
        $this->replacementRepository = $replacementRepository;
    }

    public function getAllReplacement()
    {
        return $this->replacementRepository->all();
    }
    public function getDefectByGLNumber()
    {
        return "defectBundle";
    }
}
