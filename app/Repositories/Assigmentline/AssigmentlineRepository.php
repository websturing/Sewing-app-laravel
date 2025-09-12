<?php

namespace App\Repositories\Assigmentline;

use App\Models\Assigmentline;
use Illuminate\Support\Carbon;

class AssigmentlineRepository implements AssigmentlineRepositoryInterface
{
    public function all(array $filters)
    {
        $query = Assigmentline::All();

        $query->when(
            $filters['email'] ?? null,
            fn($q, $email) => $q->where('email', 'LIKE', "%{$email}%")
        );

        $query->when(
            $filters['name'] ?? null,
            fn($q, $name) => $q->where('name', 'LIKE', "%{$name}%")
        );

        $query->when(
            ($filters['date_from'] ?? null) && ($filters['date_to'] ?? null),
            fn($q) => $q->whereBetween('created_at', [
                Carbon::parse($filters['date_from'])->startOfDay(),
                Carbon::parse($filters['date_to'])->endOfDay(),
            ])
        );

        return $query;
    }

    public function create(array $createData)
    {
        return Assigmentline::create($createData);
    }

    public function update(int $id, array $updateData)
    {
        $item = Assigmentline::findOrFail($id);
        $item->update($updateData);
        return $item;
    }

    public function delete(int $id)
    {
        $item = Assigmentline::findOrFail($id);
        if ($item) {
            return $item->delete();
        }
        return false;
    }
}
