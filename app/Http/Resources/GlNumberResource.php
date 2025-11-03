<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GlNumberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'gl_no' => $this->gl_no,
            'total_bundle' => (int) $this->total_bundle,
            'total_pcs' => (int) $this->total_pcs,
            'last_updated' => $this->last_updated,
            'line_names' => $this->line_names,
            'total_colors' => (int) $this->total_colors,
            'total_sizes' => (int) $this->total_sizes,
        ];
    }
}
