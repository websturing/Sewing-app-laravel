<?php

namespace App\Services\Replacement;

interface ReplacementServiceInterface
{
    public function getAllReplacement();
    public function getDefectByGLNumber();
    public function createReplacementRequest(array $data);
}
