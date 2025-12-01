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
        return ReplacementRequest::with(['replacementDetail', 'replacementDetail.line', 'requestedBy'])
            ->get();
    }



    public function replacmentListWithPagination(array $filters, array $lines)
    {
        $perPage = $filters['per_page'] ?? 10;
        $page = $filters['page'] ?? 1;

        $results = ReplacementRequest::with([
            'replacementDetail.line',
            'requestedBy'
        ])->whereHas('replacementDetail', function ($rd) use ($lines) {
            $rd->whereIn('line_id', $lines);
        });

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $results->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                    ->orWhereHas('replacementDetail', function ($q2) use ($search) {
                        $q2->where('color', 'like', "%{$search}%");
                    });
            });
        }

        return $results->paginate($perPage, ['*'], 'page', $page);
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
