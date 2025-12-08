<?php

namespace App\Services\CuttingGlnumber;

use App\DTOs\CuttingGLNumber\FilterDTO;

interface CuttingGlnumberServiceInterface
{
    public function getAllCuttingGlnumber(array $filters);
    public function findBy(FilterDTO $dto);
}
