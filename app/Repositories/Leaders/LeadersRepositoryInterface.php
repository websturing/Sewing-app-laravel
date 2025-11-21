<?php

namespace App\Repositories\Leaders;

interface LeadersRepositoryInterface
{
    public function all();
    public function assign(int $userId, int $lineId, int $actor);
    public function unassign(int $assignmentId, int $actor);
    public function getActiveAssignments();
    public function getAssignmentByUser();
}
