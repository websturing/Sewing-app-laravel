<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data = Arr::except($data, ['created_at', 'updated_at', 'email_verified_at', 'roles']);

        $data['role_names'] = $this->roles->map(function ($role) {
            return [
                'name' => $role->name,
                'color' => $role->color,
            ];
        });

        return $data;
    }
}
