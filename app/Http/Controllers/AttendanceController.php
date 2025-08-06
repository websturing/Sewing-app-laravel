<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
use App\Services\Attendance\AttendanceServiceInterface;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        private AttendanceServiceInterface $attendanceService
    ) {
    }

    public function index()
    {
        $result = $this->attendanceService->getAttendanceToday();
        return AttendanceResource::collection($result);
    }
}
