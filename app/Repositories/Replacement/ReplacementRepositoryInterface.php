<?php

namespace App\Repositories\Replacement;

interface ReplacementRepositoryInterface
{
    public function all();
    public function create(array $replacementRequest, array $replacementDetail);
}
