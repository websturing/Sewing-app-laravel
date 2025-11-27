<?php

namespace App\Services\Replacement;

use App\Repositories\Replacement\ReplacementRepositoryInterface;
use App\Helpers\ReplacementSerialGenerator;
use Illuminate\Support\Facades\Auth;

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

    public function createReplacementRequest(array $data)
    {
        $replacementRequest = [
            "workflow_definition_id" => 1,
            "current_step_id" => 1,
            "serial_number" => ReplacementSerialGenerator::generate($data[0]['gl_no']),
            "created_by" => Auth::id(),
            "status" => "in_progress"
        ];

        $replacementDetail = $data;

        return $this->replacementRepository->create($replacementRequest, $replacementDetail);
    }

    public function getDefectByGLNumber()
    {
        return "defectBundle";
    }
}
