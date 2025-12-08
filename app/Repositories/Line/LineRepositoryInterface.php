<?php

namespace App\Repositories\Line;

interface LineRepositoryInterface
{
    public function all(array $filters);
    public function lines();
    public function linesWithStockin();

    public function findById(array $filters);
    public function lineDevices(int $lineId);
    public function historyGlNumberByLine(int $lineId);

    public function groupByLineGlNumber(string $searchTerm, $startDate, $endDate);
    public function linesWithLastGlTransactions(array $filters);
}
