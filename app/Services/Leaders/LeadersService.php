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

    public function getAssignmentByUser()
    {
        $assignments = $this->leadersRepository->getAssignmentByUser();
        $results = $assignments->groupBy('user.name')
            ->map(function ($item, $leader) {

                $activeLines = $item
                    ->where('is_active', true) // atau nama kolom status kamu
                    ->pluck('line.name')
                    ->implode(','); // jadikan string
                $lineActives = $item->where('is_active', true)->map(function ($i) {
                    return [
                        "assign_at" => $i->assigned_at,
                        "unassigned_at" => $i->assigned_at,
                        "line_id" => $i->line->id ?? null,
                        "line_name" => $i->line->name ?? null,
                        "created_by" => $i->userCreated->name,
                        "updated_by" => $i->userUpdated->name ?? null,
                        'last_updated' => $i->updated_at
                    ];
                })->values();

                $lineInactives = $item->where('is_active', false)->map(function ($i) {
                    return [
                        "assign_at" => $i->assigned_at,
                        "unassigned_at" => $i->assigned_at,
                        "line_id" => $i->line->id ?? null,
                        "line_name" => $i->line->name ?? null,
                        "created_by" => $i->userCreated->name,
                        "updated_by" => $i->userUpdated->name ?? null,
                        'last_updated' => $i->updated_at
                    ];
                })->values();

                return [
                    "leader" => $leader,
                    "line_active" => $activeLines,
                    'last_updated' => $lineActives->max('last_updated'),
                    "line_actives" => $lineActives,
                    "line_inactives" => $lineInactives,
                ];
            })->values();

        return $results;
    }
}
