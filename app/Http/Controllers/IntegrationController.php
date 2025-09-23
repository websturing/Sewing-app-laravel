<?php

namespace App\Http\Controllers;

use App\Services\Cutting\CuttingIntegrationServiceInterface;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{

    public function __construct(
        private CuttingIntegrationServiceInterface $cuttingIntegration,
    ) {}

    public function getBundlesByTicket($ticketNumber)
    {
        $ticketData =  $this->cuttingIntegration->bundleByTicket($ticketNumber);

        return response()->json([
            "status" => true,
            "message" => "Retrived data ticket successfully",
            "data" => $ticketData['data'],
            "type" => "ticket"
        ]);
    }

    public function getBundlesByContainer($containerNumber)
    {
        $containerData = $this->cuttingIntegration->bundleByContainer($containerNumber);

        return response()->json([
            "status" => true,
            "message" => "Retrived data ticket successfully",
            "data" => $containerData['data'],
            "type" => "container"
        ]);
    }

    public function getBundleByQrcode($qrcodeNumber)
    {

        $extractQrcode = $this->cuttingIntegration->qrcodeType($qrcodeNumber);
        $qrcodetype = $extractQrcode['type'];

        switch ($qrcodetype) {
            case 'ticket':
                return $this->getBundlesByTicket($qrcodeNumber);
                break;
            case 'container':
                return $this->getBundlesByContainer($qrcodeNumber);
                break;
            default:
                return response()->json(['error' => 'Type harus ticket dan container'], 400);
        }
    }
}
