<?php

namespace App\Repositories\Glnumber;

interface GlnumberRepositoryInterface
{
    public function all(array $filters);
    public function findGlNumber(string $glNumber);
    public function glNumberWithColor(?string $glNumber);

    public function getGlNumberGroup(array $filters);
}
