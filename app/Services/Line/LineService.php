<?php

namespace App\Services\Line;

use App\Repositories\Line\LineRepositoryInterface;

class LineService implements LineServiceInterface
{
    protected $lineRepository;

    public function __construct(LineRepositoryInterface $lineRepository)
    {
        $this->lineRepository = $lineRepository;
    }

    public function getLines()
    {
        return $this->lineRepository->lines();
    }

    public function getLinesWithStockin()
    {
        return $this->lineRepository->linesWithStockin();
    }

    public function getAllLine(array $filters)
    {
        return $this->lineRepository->all($filters)
            ->orderByRaw('CAST(SUBSTRING(name, 6) AS UNSIGNED) ASC');
    }

    public function getLinePaginate(array $filters)
    {
        return $this->lineRepository->all($filters)
            ->orderByRaw('CAST(SUBSTRING(name, 2) AS UNSIGNED) ASC')
            ->paginate($filters['per_page'] ?? 100);
    }

    public function groupByLineGlNumber(string $searchTerm, $startDate, $endDate)
    {
        return $this->lineRepository->groupByLineGlNumber($searchTerm, $startDate, $endDate);
    }

    public function linesWithLastGlTransactions(array $filters)
    {

        return $this->lineRepository->linesWithLastGlTransactions($filters);
    }

    /**
     * LINES GET BY
     * Get Line with last GL Number Transactions .
     *
     * @param integer|null $lineId
     * @return LengthAwarePaginator
     */

    public function getById($filters)
    {
        return $this->lineRepository->findById($filters);
    }
}
