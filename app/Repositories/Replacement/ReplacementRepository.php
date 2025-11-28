<?php

namespace App\Repositories\Replacement;

use App\Models\Replacement;
use App\Models\ReplacementRequest;
use App\Models\ReplacementRequestDetail;
use Carbon\Carbon;

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
            ->map(fn($e) => $this->transform($e));
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
            ->through(fn($e) => $this->transform($e));
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

    private function transform($e)
    {
        $defectList = $e->replacementDetail
            ->groupBy('color')
            ->map(function ($d, $color) {

                return [
                    "color" => $color,
                    "laying_planning_id" => $d->first()->laying_planning_id,
                    "total_defect" => $d->sum('pcs'),
                    "size_list" => $d->map(fn($s) => [
                        "size" => $s->size,
                        "defect_qty" => $s->pcs
                    ])
                ];
            })->values();

        return [
            "serial_number" => $e->serial_number,
            "gl_no" => $e->replacementDetail->first()->gl_no,
            "line_names" => $e->replacementDetail->pluck('line.name')->unique(),
            "defect_list" => $defectList,
            "defect_total" => $defectList->sum('total_defect'),
            "is_approval" => false,
            "created_at" => Carbon::parse($e->created_at)->format("F d,Y H:i"),
            "updated_at" => Carbon::parse($e->updated_at)->format("F d,Y H:i")
        ];
    }
}
