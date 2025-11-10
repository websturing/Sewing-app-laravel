<?php

namespace App\Http\Controllers;

use App\Http\Resources\lineDeviceResource;
use Illuminate\Http\Request;
use App\Services\Line\LineServiceInterface;

class lineDeviceController extends Controller
{

    public function __construct(
        private LineServiceInterface $lineService,
    ) {}

    public function getLineDevices(int $lineId)
    {
        $results = $this->lineService->getLineDevices($lineId);

        return lineDeviceResource::collection($results)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved Line Devices'
        ]);
    }
}
