<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityGroupDateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this['date'],
            'date_formatted' => \Carbon\Carbon::parse($this['date'])->translatedFormat('d F Y'),
            'total' => $this['total'],
            'items' => ActivityResource::collection($this['items'])
        ];
    }
}
