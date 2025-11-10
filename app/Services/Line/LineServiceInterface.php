<?php

namespace App\Services\Line;

interface LineServiceInterface
{
    public function getAllLine(array $filters);
    public function getLines();
    public function getLinesWithStockin();
    public function getLinePaginate(array $filters);

    public function getById(array $filters);
    public function getLineDevices(int $lineId);


    public function linesWithLastGlTransactions(array $filters);
    public function groupByLineGlNumber(string $searchTerm, $startDate, $endDate);
}
