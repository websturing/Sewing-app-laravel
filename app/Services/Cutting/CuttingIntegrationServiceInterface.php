<?php

namespace App\Services\Cutting;

interface CuttingIntegrationServiceInterface
{
    public function summaryGlNumber(array $filters);
    public function bundleByTicket(String $ticketNumber);
    public function bundleByContainer(String $containerNumber);
    public function qrcodeType(String $serialNumber);
}
