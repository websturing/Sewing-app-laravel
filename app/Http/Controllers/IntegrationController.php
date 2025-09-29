<?php

namespace App\Http\Controllers;

use App\Services\Cutting\CuttingIntegrationServiceInterface;
use App\Services\Stockin\StockinServiceInterface;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{

    public function __construct(
        private CuttingIntegrationServiceInterface $cuttingIntegration,
        private StockinServiceInterface $stockInService,
    ) {}

    public function getBundlesByTicket($ticketNumber)
    {
        $ticketData =  $this->cuttingIntegration->bundleByTicket($ticketNumber);

        if (isset($ticketData['data'])) {
            return response()->json([
                "status" => true,
                "message" => "Retrived data ticket successfully",
                "data" => $ticketData['data'],
                "type" => "ticket"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ticket Not Found",
            ], 404);
        }
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


        $stockIn = $this->stockInService->getBySerialNumber($qrcodeNumber);

        if ($stockIn) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket Already Exist',
                'type' => 'error'
            ], 409);
        }



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
                return response()->json([
                    'status' => false,
                    'message' => 'Type harus ticket dan container',
                    'type' => 'error'
                ], 400);
        }
    }
}
