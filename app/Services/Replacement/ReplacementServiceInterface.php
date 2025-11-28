<?php

namespace App\Services\Replacement;

interface ReplacementServiceInterface
{
    public function getAllReplacement();
    public function getDefectByGLNumber();
    public function getReplacementList();
    public function getReplacementListWithPagination(array $filters);
    public function createReplacementRequest(array $data);
}
