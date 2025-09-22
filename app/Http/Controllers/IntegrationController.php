<?php

namespace App\Http\Controllers;

use App\Services\Cutting\CuttingIntegrationServiceInterface;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{

    public function __construct(
        private CuttingIntegrationServiceInterface $cuttingIntegration,
    ) {}

    public function getBundlesByTicket(Request $request)
    {
        $ticketNumber = $request->get('ticket_number');
        return $this->cuttingIntegration->bundleByTicket($ticketNumber);
    }
}
