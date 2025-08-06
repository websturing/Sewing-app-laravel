<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "date" => $this->attendance_date,
            "check_in" => Carbon::parse($this->check_in_time)->format('H:i:s'),
            "check_out" => Carbon::parse($this->check_out_time)->format('H:i:s'),
            "working_hours" => $this->calculateWorkingHours(),
            "status" => $this->status,
            "user" => $this->whenLoaded('user') ? $this->user->name : null,
            "logs" => AttendanceLogResource::collection($this->whenLoaded('logs')),
            "log_count" => $this->logs_count
        ];
    }
}
