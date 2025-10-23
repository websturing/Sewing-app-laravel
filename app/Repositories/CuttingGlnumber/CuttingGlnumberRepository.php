<?php

namespace App\Repositories\CuttingGlnumber;

use App\Models\CuttingGlSummary;

class CuttingGlnumberRepository implements CuttingGlnumberRepositoryInterface
{
    public function all(?string $glNumber = null, ?string $color = null)
    {
        $query = CuttingGlSummary::with(['colors.sizes'])
            ->when($glNumber, fn($q) => $q->where('gl_number', $glNumber))
            ->when($color, fn($q) => $q->whereHas('colors', fn($q2) =>    $q2->whereRaw('BINARY color = ?', [$color])));

        return $query->get();
    }

    public function allWithSearching(?string $glNumber = null, ?array $colors = null)
    {
        $query = CuttingGlSummary::with([
            'colors' => fn($q) => $colors
                ? $q->whereIn('color', $colors)->with('sizes')
                : $q->with('sizes')
        ])
            ->when($glNumber, fn($q) => $q->where('gl_number', $glNumber))
            ->when($colors, fn($q) => $q->whereHas('colors', fn($q2) => $q2->whereIn('color', $colors)));

        return $query->first();
    }
}
