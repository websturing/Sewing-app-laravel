<?php

namespace App\Services\Defect;

interface DefectServiceInterface
{
    public function getAllDefect();
    public function getSummaryGroupByLines();
    public function getGroupGlNumber();
}
