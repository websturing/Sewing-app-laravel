<?php

namespace App\Services\Line;

interface LineServiceInterface
{
    public function getAllLine(array $filters);
    public function getLinePaginate(array $filters);
}
