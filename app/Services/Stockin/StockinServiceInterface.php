<?php

namespace App\Services\Stockin;

interface StockinServiceInterface
{
    public function getByQuery(array $filters);
    public function getAllStockin(array $params);
    public function activityGroupByGL(array $params);
    public function getBySerialNumber(string $serialNumber);
    public function getPaginate(array $filters);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function lastStockInTicketByLine(int $lineId);

    public function getTickets(array $filters);
    public function activity(array $filters);

    public function summary(array $filters);
}
