<?php

namespace App\Repositories\Glnumber;

use App\Models\GlNumber;

class GlnumberRepository implements GlnumberRepositoryInterface
{
    public function all(array $filters)
    {
        $query = GLNumber::query();



        $query->when(
            $filters['q'] ?? null,
            fn($q, $name) => $q->where('gl_number', 'LIKE', "%{$name}%")
        );


        return $query;
    }

    public function findGlNumber(string $glNumber)
    {
        return GLNumber::where('gl_number', $glNumber)->first();
    }
}
