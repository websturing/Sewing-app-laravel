<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $data = parent::toArray($request);
        // return Arr::except($data, ['created_at', 'updated_at', 'user']);

        return [
            'id'            => $this->id,
            'employee_code' => $this->employee_code,
            'position'      => $this->position,
            'department'    => $this->department,
            'join_date'     => $this->join_date
                ? Carbon::parse($this->join_date)->format('Y-m-d')
                : null,
            'active'        => $this->active,
            'device_id'     => $this->device_id,

            // Relasi user (optional jika ada)
            'user' => $this->whenLoaded('user', fn() => new UserResource($this->user)),
        ];
    }
}
