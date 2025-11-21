<?php

namespace App\Services\Leaders;

use App\Repositories\Leaders\LeadersRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LeadersService implements LeadersServiceInterface
{
    protected $leadersRepository;

    public function __construct(LeadersRepositoryInterface $leadersRepository)
    {
        $this->leadersRepository = $leadersRepository;
    }

    public function getAllLeaders()
    {
        return $this->leadersRepository->all();
    }

    public function createAssign(array $request)
    {
        $userId = $request['user_id'];
        $lineId = $request['line_id'];
        $actor = Auth::id();
        return $this->leadersRepository->assign($userId, $lineId, $actor);
    }

    public function createUnassign(array $request)
    {
        $assignmentId = $request['id'];
        $actor = Auth::id();
        return $this->leadersRepository->unassign($assignmentId, $actor);
    }

    public function getAssignmentSummaryByLeader()
    {
        $assignments = $this->leadersRepository->summaryByLeader();

        return $assignments
            ->groupBy('user.name')
            ->map(function ($records, $leaderName) {

                $active = $records->where('is_active', true);
                $inactive = $records->where('is_active', false);

                $activeLinesString = $active
                    ->pluck('line.name')
                    ->implode(', ');

                $mapLine = function ($item) {
                    return [
                        "assign_at"      => $item->assigned_at,
                        "unassign_at"    => $item->unassigned_at,
                        "line_id"        => $item->line->id ?? null,
                        "line_name"      => $item->line->name ?? null,
                        "created_by"     => $item->userCreated->name,
                        "updated_by"     => $item->userUpdated->name ?? null,
                        "last_updated"   => $item->updated_at,
                    ];
                };

                $activeDetails = $active->map($mapLine)->values();
                $inactiveDetails = $inactive->map($mapLine)->values();

                return [
                    "leader"        => $leaderName,
                    "active_lines"  => $activeLinesString,
                    "last_updated"  => $activeDetails->max('last_updated'),
                    "active_detail" => $activeDetails,
                    "inactive_detail" => $inactiveDetails,
                ];
            })
            ->values();
    }
}
