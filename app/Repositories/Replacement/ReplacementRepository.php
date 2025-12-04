<?php

namespace App\Repositories\Replacement;

use App\Models\Replacement;
use App\Models\ReplacementRequest;
use App\Models\ReplacementRequestDetail;
use App\Models\ReplacementRequestHistory;
use App\Models\ReplacementRequestNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

    public function replacementGlNumber(string $glNumber)
    {
        return ReplacementRequestDetail::selectRaw('gl_no, color, size, SUM(pcs) as pcs')
            ->where('gl_no', $glNumber)
            ->groupBy('gl_no', 'color', 'size')
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

    public function replacementApprovalListWithPagination(array $filters, array $lines, array $roles)
    {
        $perPage = $filters['per_page'] ?? 10;
        $page = $filters['page'] ?? 1;



        $results = ReplacementRequest::with([
            'replacementDetail.line',
            'requestedBy',
            'workflowStep'
        ])->whereHas('workflowStep', function ($rd) use ($roles) {
            $rd->whereIn('role_id_responsible', $roles);
        })->whereHas('replacementDetail', function ($rd) use ($lines) {
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

    public function findHistoriesByReplacementId($replacementId)
    {
        return ReplacementRequestHistory::where('replacement_request_id', $replacementId)
            ->with(['workflowStep', 'createdBy'])
            ->get();
    }

    public function create(array $replacementRequest)
    {

        return ReplacementRequest::create($replacementRequest);
    }

    public function createReplacementDetail(array $data)
    {
        return ReplacementRequestDetail::create($data);
    }
    public function createReplacementNote(array $data)
    {
        return ReplacementRequestNote::create($data);
    }

    public function createReplacementHistory(array $data)
    {
        return ReplacementRequestHistory::create($data);
    }
}
