<?php

namespace App\Repositories\Line;

use App\Models\Line;
use Illuminate\Support\Carbon;

class LineRepository implements LineRepositoryInterface
{
    public function all(array $filters)
    {
        $query = Line::query();

        $query->when(
            $filters['q'] ?? null,
            fn($q, $name) => $q->where('name', 'LIKE', "%{$name}%")
        );


        return $query; // <--- jangan pakai get()
    }

    public function lines()
    {
        return Line::all();
    }

    public function linesWithStockin()
    {
        return Line::with(['stockins'])->get();
    }
}
