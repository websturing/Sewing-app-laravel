<?php

namespace App\Services\Cutting;

use Illuminate\Support\Facades\Http;


class CuttingIntegrationService implements CuttingIntegrationServiceInterface
{

    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('cuttingapi.url');
        $this->token = config('cuttingapi.token');
    }

    public function summaryGlNumber(array $filters)
    {

        $summaryGlNumber = $this->get('summary-by-gl', [
            'gl_number' => $filters['gl_number'] ?? null,
        ]);
        return $summaryGlNumber;
    }

    public function bundleByTicket(string $ticketNumber)
    {
        $item = $this->get('bundle-qr-code-scan', [
            'serial_number' => $ticketNumber
        ]);
        return $item;
    }

    public function bundleByContainer(string $containerNumber)
    {
        $item = $this->get('bundle-by-container', [
            'container_serial_number' => $containerNumber
        ]);
        return $item;
    }

    public function qrcodeType(string $serialNumber)
    {
        $prefix = substr($serialNumber, 0, 3);
        if (str_starts_with($prefix, 'CT-')) {
            return [
                'type' => 'ticket',
                'serial_number' => $serialNumber,
                'valid' => true,
                'message' => 'QR code terdeteksi sebagai TICKET'
            ];
        }

        if (str_starts_with($prefix, 'CTA')) {
            return [
                'type' => 'container',
                'serial_number' => $serialNumber,
                'valid' => true,
                'message' => 'QR code terdeteksi sebagai CONTAINER'
            ];
        }

        if (str_starts_with($prefix, 'SW-')) {
            return [
                'type' => 'table',
                'serial_number' => $serialNumber,
                'valid' => true,
                'message' => 'QR code terdeteksi sebagai PHYSICAL TABLE'
            ];
        }

        return [
            'type' => 'unknown',
            'serial_number' => $serialNumber,
            'valid' => false,
            'message' => 'Format QR code tidak dikenali'
        ];
    }


    public function get(string $endpoint, array $params = [])
    {
        return Http::get($this->baseUrl . '/' . ltrim($endpoint, '/'), $params)
            ->json();
    }
}
