<?php

namespace App\Repositories\Replacement;

interface ReplacementRepositoryInterface
{
    public function all();
    public function create(array $replacementRequest);
    public function createReplacementDetail(array $data);
    public function createReplacementNote(array $data);
    public function createReplacementHistory(array $data);
    public function updateReplacementRequest(int $replacementRequestId, array $payload);

    public function findReplacementRequestId(int $id);

    public function replacmentList();
    public function findHistoriesByReplacementId(int $replacementId);
    public function replacementGlNumber(string $glNumber);
    public function replacmentListWithPagination(array $filters, array $lines);
    public function replacementApprovalListWithPagination(array $filters, array $lines, array $roles);
}
