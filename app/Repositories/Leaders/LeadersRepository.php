<?php

namespace App\Repositories\Leaders;

use App\Models\Leader;
use App\Models\Leaders;
use Illuminate\Support\Facades\DB;

class LeadersRepository implements LeadersRepositoryInterface
{
    public function all()
    {
        return Leader::all();
    }

    public function assign(int $userId, array $lineIds, int $actor)
    {
        return DB::transaction(function () use ($userId, $lineIds, $actor) {

            $results = [];

            foreach ($lineIds as $lineId) {
                // nonaktifkan assignment lama kalau ada
                Leader::where('user_id', $userId)
                    ->where('line_id', $lineId)
                    ->whereNull('unassigned_at')
                    ->update([
                        'unassigned_at' => now(),
                        'is_active' => false,
                        'updated_by' => $actor
                    ]);

                // buat assignment baru
                $results[] = Leader::create([
                    'user_id' => $userId,
                    'line_id' => $lineId,
                    'assigned_at' => now(),
                    'is_active' => true,
                    'created_by' => $actor
                ]);
            }

            return $results;
        });
    }


    public function unassign(int $assignmentId, int $actor)
    {
        return Leader::where('id', $assignmentId)
            ->update([
                'unassigned_at' => now(),
                'is_active' => false,
                'updated_by' => $actor
            ]);
    }

    public function getActiveAssignments()
    {
        return Leader::with(['leader', 'line'])
            ->where('is_active', true)
            ->get();
    }

    public function getActiveAssignmentsByUserId(int $userId)
    {
        return Leader::with(['user', 'line', 'userCreated', 'userUpdated'])
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->get();
    }

    public function summaryByLeader()
    {
        return Leader::with(['user', 'line', 'userCreated', 'userUpdated'])->get();
    }
}
