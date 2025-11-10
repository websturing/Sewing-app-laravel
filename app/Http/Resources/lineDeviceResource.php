<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class lineDeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'line_name' => $this->line->name,
            'device_id' => $this->device->id,
            'device_name' => $this->device->name,
            'device_mac_address' => $this->device->mac_address,
            'updated_at' => $this->updated_at,
        ];
    }
}
