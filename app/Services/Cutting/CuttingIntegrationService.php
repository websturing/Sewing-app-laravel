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

    public function get(string $endpoint, array $params = [])
    {
        return Http::get($this->baseUrl . '/' . ltrim($endpoint, '/'), $params)
            ->json();
    }
}
