<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data = Arr::except($data, ['created_at', 'updated_at', 'causer_type', 'causer_id', 'subject_id', 'causer']);

        $data['causer'] = $this->getCauserInfo();
        return $data;
    }

    protected function getCauserInfo()
    {
        // Jika causer terload dan ada
        if ($this->relationLoaded('causer') && $this->causer) {
            $name =  $this->causer->name ?? 'Unknown User';
            $email = $this->causer->email ?? 'No email';

            return $name . '(' . $email . ')';
        }

        // Jika causer tidak terload, tapi ada causer_id
        if ($this->causer_id && $this->causer_type) {
            $name =  $this->causer->name ?? 'Unknown User';
            $email = $this->causer->email ?? 'No email';

            return $name . ' (' . $email . ')';
        }

        // Jika tidak ada causer sama sekali
        return null;
    }
}
