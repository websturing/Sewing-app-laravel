<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Http\Resources\ShiftResources;
use App\Services\Shift\ShiftServiceInterface;
use Illuminate\Http\Request;

class ShiftController extends Controller
{

    public function __construct(
        private ShiftServiceInterface $shiftService
    ) {}


    public function index()
    {
        $shift = $this->shiftService->getAllShift();
        return ShiftResources::collection($shift);
    }

    public function createShift(ShiftRequest $request)
    {
        $shift = $this->shiftService->createShift($request->validated());
        return response()->json($shift, 201);
    }
    public function updateShift(ShiftRequest $request, $shiftId)
    {
        $shift = $this->shiftService->updateShift($shiftId, $request->validated());
        return response()->json($shift, 201);
    }

    public function deleteShift($shiftId)
    {
        $shift = $this->shiftService->deleteShift($shiftId);
        return response()->json($shift, 201);
    }
}
