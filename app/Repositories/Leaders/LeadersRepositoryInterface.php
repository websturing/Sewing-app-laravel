<?php

namespace App\Repositories\Leaders;

interface LeadersRepositoryInterface
{
    public function all();
    public function assign(int $userId, array $lineId, int $actor);
    public function unassign(int $assignmentId, int $actor);
    public function getActiveAssignments();
    public function summaryByLeader();
}
