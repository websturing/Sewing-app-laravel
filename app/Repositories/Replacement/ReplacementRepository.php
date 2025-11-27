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
