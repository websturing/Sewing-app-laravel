<?php

namespace App\Repositories\Stockin;

use App\Models\Stockin;

class StockinRepository implements StockinRepositoryInterface
{
    public function all()
    {
        return Stockin::all();
    }

    public function paginateAll(array $filters)
    {
        $query = Stockin::query();

        $query->when(
            $filters['gl_number'] ?? null,
            fn($q, $glNumber) => $q->where('gl_number', 'LIKE', "%{$glNumber}%")
        );

        $query->when(
            $filters['serial_number'] ?? null,
            fn($q, $serialNumber) => $q->where('serial_number', 'LIKE', "%{$serialNumber}%")
        );

        $query->when(
            $filters['color'] ?? null,
            fn($q, $color) => $q->where('color', 'LIKE', "%{$color}%")
        );

        return $query;
    }
}
