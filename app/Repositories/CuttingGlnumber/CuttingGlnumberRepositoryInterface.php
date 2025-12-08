<?php

namespace App\Repositories\CuttingGlnumber;

interface CuttingGlnumberRepositoryInterface
{
    public function all(?string $glNumber = null, ?string $color = null);
    public function allWithSearching(?string $glNumber = null, ?array $color = null);
}
