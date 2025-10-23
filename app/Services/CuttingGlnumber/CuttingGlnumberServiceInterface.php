<?php

namespace App\Services\CuttingGlnumber;

interface CuttingGlnumberServiceInterface
{
    public function getAllCuttingGlnumber(array $filters);
    public function findBy(array $filters);
}
