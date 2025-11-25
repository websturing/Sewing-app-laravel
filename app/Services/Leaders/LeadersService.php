<?php

namespace App\Services\Leaders;

use App\Repositories\Leaders\LeadersRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

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

                // active line names for string summary
                $activeLinesString = $active
                    ->pluck('line.name')
                    ->implode(', ');

                $activeLinesIds = $active
                    ->pluck('line.id')
                    ->implode(', ');

                // reusable local mapper (compact & readable)
                $mapLine = fn($item) => [
                    "assign_id"      => $item->id,
                    "assign_at"      => Carbon::parse($item->assigned_at)->format("F d, Y H:i"),
                    "unassign_at"    => $item->unassigned_at,
                    "line_id"        => $item->line->id ?? null,
                    "line_name"      => $item->line->name ?? null,
                    "created_by"     => $item->userCreated->name,
                    "updated_by"     => $item->userUpdated->name ?? null,
                    "last_updated"   => Carbon::parse($item->updated_at)->format("F d, Y H:i"),
                ];

                $activeDetails = $active->map($mapLine)->values();
                $inactiveDetails = $inactive->map($mapLine)->values();

                // prevent Carbon parse error if no active record
                $lastUpdated = $activeDetails->max('last_updated');
                $lastUpdated = $lastUpdated
                    ? Carbon::parse($lastUpdated)->format("F d, Y H:i")
                    : null;

                return [
                    "leader"          => $leaderName,
                    "leader_id"       => $records->first()->user_id,
                    "is_active"       => (bool)$records->first()->is_active,
                    "active_lines"    => $activeLinesString,
                    "active_line_ids"  => $activeLinesIds,
                    "last_updated"    => $lastUpdated,
                    "active_detail"   => $activeDetails,
                    "inactive_detail" => $inactiveDetails,
                ];
            })
            ->values();
    }

    public function getActiveAssignmentsByUserId(int $userId)
    {
        $assignments = $this->leadersRepository->getActiveAssignmentsByUserId($userId);
        return $assignments
            ->groupBy('user.name')
            ->map(function ($records, $leaderName) {

                $active = $records->where('is_active', true);
                $inactive = $records->where('is_active', false);

                // active line names for string summary
                $activeLinesString = $active
                    ->pluck('line.name')
                    ->implode(', ');

                $activeLinesIds = $active
                    ->pluck('line.id')
                    ->implode(', ');

                // reusable local mapper (compact & readable)
                $mapLine = fn($item) => [
                    "assign_id"      => $item->id,
                    "assign_at"      => Carbon::parse($item->assigned_at)->format("F d, Y H:i"),
                    "unassign_at"    => $item->unassigned_at,
                    "line_id"        => $item->line->id ?? null,
                    "line_name"      => $item->line->name ?? null,
                    "created_by"     => $item->userCreated->name,
                    "updated_by"     => $item->userUpdated->name ?? null,
                    "last_updated"   => Carbon::parse($item->updated_at)->format("F d, Y H:i"),
                ];

                $activeDetails = $active->map($mapLine)->values();
                $inactiveDetails = $inactive->map($mapLine)->values();

                // prevent Carbon parse error if no active record
                $lastUpdated = $activeDetails->max('last_updated');
                $lastUpdated = $lastUpdated
                    ? Carbon::parse($lastUpdated)->format("F d, Y H:i")
                    : null;

                return [
                    "leader"          => $leaderName,
                    "leader_id"       => $records->first()->user_id,
                    "is_active"       => (bool)$records->first()->is_active,
                    "active_lines"    => $activeLinesString,
                    "active_line_ids"  => $activeLinesIds,
                    "last_updated"    => $lastUpdated,
                    "active_detail"   => $activeDetails,
                    "inactive_detail" => $inactiveDetails,
                ];
            })
            ->values();
    }
}
