<?php

namespace App\Services\Glnumber;

interface GlnumberServiceInterface
{
    public function getAllGlnumber(array $filters);
    public function getPaginate(array $filters);
    public function findGlNumber(string $glNumber);
}
