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
            $filters['q'] ?? null,
            fn($q, $query) => $q->where('gl_no', 'LIKE', "%{$query}%")
                ->orWhere('serial_number', 'LIKE', "%{$query}%")
                ->orWhere('color', 'LIKE', "%{$query}%")
        );



        return $query;
    }
}
