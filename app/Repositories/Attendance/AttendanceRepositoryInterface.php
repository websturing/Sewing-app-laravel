<?php

namespace App\Repositories\Attendance;

interface AttendanceRepositoryInterface
{
    public function all();
    public function today();
}
