<?php

namespace App\Services\Replacement;

interface ReplacementServiceInterface
{
    public function createApprovalByRole(int $replacementRequestId, string $action, ?string $note);
    public function getAllReplacement();
    public function getDefectByGLNumber(string $glNumber);
    public function getReplacementList();
    public function getReplacementListWithPagination(array $filters);
    public function getApprovalWithPagination(array $filters);
    public function getTicketTrackingBySerial(string $serialNumber);

    public function createReplacementRequest(array $data);
    public function getHistoriesByReplacementId(int $replacementId);
}
