<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class AssignmentLineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $item = parent::toArray($request);
        $item = Arr::except($item, ['created_at', 'updated_at', 'line', 'glnumber']);

        $item['line'] = $this->whenLoaded('line', fn() => $this->line->name);
        $item['glnumber'] = $this->whenLoaded('glnumber', fn() => $this->glnumber->gl_number);

        return $item;
    }
}
