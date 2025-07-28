<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ShiftResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $data = parent::toArray($request);
        // return Arr::except($data, ['created_at', 'updated_at']);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "is_night_shift" => $this->is_night_shift,
            "tolerance" => $this->tolerance,
            "tolerance_breakdown" => $this->tolerance_break_down
        ];
    }
}
