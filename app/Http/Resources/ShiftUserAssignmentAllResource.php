<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftUserAssignmentAllResource extends JsonResource
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
            'user_id' => $this->user_id,
            'shift_id' => $this->shift_id,
            'user' => $this->whenLoaded('user') ? $this->user->name : null,
            'shift' => $this->whenLoaded('shift') ? $this->shift->name : null,
            'time' => $this->whenLoaded('shift') ? $this->shift->start_time . ' - ' . $this->shift->end_time : null,
            'date_start' => $this->effective_date_start,
            'date_end' => $this->effective_date_end ?? "-"
        ];
    }
}
