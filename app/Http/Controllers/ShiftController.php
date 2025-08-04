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

        if (!$shift) {
            return errorResponse('Shift not found', 404);
        }
        $collection = ShiftResources::collection($shift);
        return successResponse($collection);
    }

    public function getAssignments(){
        return "getAssignments";
    }

    public function createShift(ShiftRequest $request)
    {
        $shift = new ShiftResources($this->shiftService->createShift($request->validated()));
        return successResponse($shift, 'Succesfully Created Shift : ' . $shift->name);
    }
    public function updateShift(ShiftRequest $request, $shiftId)
    {
        $shift = new ShiftResources($this->shiftService->updateShift($shiftId, $request->validated()));
        return successResponse($shift, "Succesfully Updated Shift : " . $shift->name);
    }

    public function deleteShift($shiftId)
    {
        $deletedShift = $this->shiftService->deleteShift($shiftId);
        if (!$deletedShift) {
            return errorResponse('Shift not found', 404);
        }

        return successResponse(
            new ShiftResources($deletedShift),
            "Successfully deleted shift: {$deletedShift->name}"
        );
    }
}
