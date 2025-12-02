<?php

namespace App\Repositories\Replacement;

interface ReplacementRepositoryInterface
{
    public function all();
    public function create(array $replacementRequest, array $replacementDetail);

    public function replacmentList();
    public function replacementGlNumber(string $glNumber);
    public function replacmentListWithPagination(array $filters, array $lines);
    public function replacementApprovalListWithPagination(array $filters);
}
