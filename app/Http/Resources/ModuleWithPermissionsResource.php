<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleWithPermissionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'permissions' => ModulePermissionsResources::collection(
                $this->permissions->sortBy(function ($permission) {
                    $order = ['read', 'create', 'update', 'delete', 'upload', 'download'];
                    return array_search($permission->action, $order);
                })
            ),
            'children' => self::collection($this->children),
        ];
    }
}
