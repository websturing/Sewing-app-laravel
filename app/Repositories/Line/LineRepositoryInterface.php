<?php

namespace App\Repositories\Line;

interface LineRepositoryInterface
{
    public function all(array $filters);
    public function lines();
    public function linesWithStockin();

    public function groupByLineGlNumber(string $searchTerm, $startDate, $endDate);
    public function linesWithLastGlTransactions(array $filters);
}
