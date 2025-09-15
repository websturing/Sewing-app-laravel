<?php

namespace App\Http\Resources;

use Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LineAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $data =  parent::toArray($request);
        $data = Arr::except($data, ['created_at', 'updated_at', 'assignment']);

        $data['assignment'] = $this->whenLoaded('assignment', fn() => AssignmentLineResource::collection($this->assignment));

        return $data;
    }
}
