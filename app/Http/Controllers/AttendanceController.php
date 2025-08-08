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
        $result = $this->attendanceService->getAllAttendance();
        return AttendanceResource::collection($result);
    }

    public function getAttendanceToday()
    {
        $result = $this->attendanceService->getAttendanceToday();
        $data = [
            "summary" => $result['summary'],
            'check_in_average' => $result['check_in_average'],
            'check_in_analytics' => $result['check_in_percentage'],
            'shift_statistics' => $result['shift_average'],
            "records" => AttendanceResource::collection($result['items'])
        ];
        return successResponse($data);
    }
}
