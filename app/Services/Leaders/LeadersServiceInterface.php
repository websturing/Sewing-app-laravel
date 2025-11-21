<?php

namespace App\Services\Leaders;

interface LeadersServiceInterface
{
    public function getAllLeaders();
    public function createAssign(array $request);
    public function createUnassign(array $request);

    public function getAssignmentSummaryByLeader();
}
