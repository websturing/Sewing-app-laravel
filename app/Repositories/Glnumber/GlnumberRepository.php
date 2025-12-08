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

    public function glNumberWithColor(?string $glNumber)
    {
        if ($glNumber) {
            $gl = GLNumber::with([
                'stockIns' => function ($q) {
                    $q->select('gl_no', 'color')->distinct();
                }
            ])
                ->where('gl_number', $glNumber) // <– glNumber spesifik
                ->firstOrFail();

            return [
                'gl_number' => $gl->gl_number,
                'colors'    => $gl->stockIns
                    ->pluck('color')
                    ->unique()
                    ->values(),
            ];
        } else {
            $gls = GLNumber::with([
                'stockIns' => function ($q) {
                    $q->select('gl_no', 'color')->distinct();
                }
            ])->get();
            return $gls->map(function ($gl) {
                return [
                    'gl_number' => $gl->gl_number,
                    'colors'    => $gl->stockIns->pluck('color')->unique()->values(),
                    'color_count' => $gl->stockIns->pluck('color')->unique()->count(),
                ];
            });
        }
    }

    public function getGlNumberGroup(array $filters)
    {
        $start = $filters['start_date'] ?? null;
        $end   = $filters['end_date'] ?? null;
        $color = $filters['color'] ?? null;
        $glNumber = $filters['gl_number'];

        $glResult  =  GlNumber::where('gl_number', $glNumber)->first();
        $grouped = $glResult->glNumberByStockIns($start, $end, $color);

        return $grouped;
    }
}
