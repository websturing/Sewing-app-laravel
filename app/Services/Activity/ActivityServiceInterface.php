<?php

namespace App\Services\Activity;

use Illuminate\Support\Collection;

interface ActivityServiceInterface
{
    public function getAllActivity(array $filters);
    public function getActivitiesByUser(int $userId): Collection;
}
