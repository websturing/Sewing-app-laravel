<?php

namespace App\Repositories\Replacement;

use App\Models\Replacement;
use App\Models\ReplacementRequest;
use App\Models\ReplacementRequestDetail;

class ReplacementRepository implements ReplacementRepositoryInterface
{
    public function all()
    {
        return ReplacementRequest::all();
    }

    public function replacmentList()
    {
        return ReplacementRequest::with(['replacementDetail', 'replacementDetail.line'])
            ->get()
            ->map(function ($e) {

                $defectList = $e->replacementDetail->groupBy('color')->map(function ($d, $color) {

                    $sizeList = $d->map(function ($s) {
                        return [
                            "size" => $s->size,
                            "defect_qty" => $s->pcs
                        ];
                    });

                    return [
                        "color" => $color,
                        "laying_planning_id" => $d->pluck('laying_planning_id')->unique()->first(),
                        "size_list" => $sizeList
                    ];
                });

                return [
                    "serial_number" => $e->serial_number,
                    "gl_no" => $e->replacementDetail->first()->gl_no,
                    "line_name" => $e->replacementDetail->pluck('line.name')->unique(),
                    "defect_list" => $defectList,
                    "is_approval" => false
                ];
            });
    }



    public function replacmentListWithPagination(array $filters)
    {

        $perPage = $filters['per_page'] ?? 10;
        $page = $filters['page'] ?? 1;

        $results =  ReplacementRequest::with(['replacementDetail', 'replacementDetail.line']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $results->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                    ->orWhereHas('replacementDetail', function ($q2) use ($search) {
                        $q2->where('color', 'like', "%{$search}%");
                    });
            });
        }


        return $results
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(function ($e) {

                $defectList = $e->replacementDetail->groupBy('color')->map(function ($d, $color) {

                    $sizeList = $d->map(function ($s) {
                        return [
                            "size" => $s->size,
                            "defect_qty" => $s->pcs
                        ];
                    });

                    return [
                        "color" => $color,
                        "laying_planning_id" => $d->pluck('laying_planning_id')->unique()->first(),
                        "size_list" => $sizeList
                    ];
                })->values();

                return [
                    "serial_number" => $e->serial_number,
                    "gl_no" => $e->replacementDetail->first()->gl_no,
                    "line_name" => $e->replacementDetail->pluck('line.name')->unique(),
                    "defect_list" => $defectList,
                    "is_approval" => false
                ];
            });
    }


    public function create(array $replacementRequest, array $replacementDetail)
    {

        $replacement = ReplacementRequest::create($replacementRequest);

        foreach ($replacementDetail as $detail) {
            ReplacementRequestDetail::create([
                'gl_no' => $detail['gl_no'],
                'size' => $detail['size'],
                'color' => $detail['color'],
                'pcs' => $detail['total_defect'],
                'line_id' => $detail['line_id'],
                'laying_planning_id' => $detail['laying_planning_id'],
                'replacement_request_id' => $replacement->id,
                "description" => ""
            ]);
        }
    }
}
