<?php

namespace App\Repositories\Activity;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Activitylog\Models\Activity;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function query(): Builder
    {
        return Activity::query();
    }

    public function all(): Collection
    {
        return Activity::all();
    }

    public function getByUser(int $userId): Collection
    {
        return Activity::where('causer_id', $userId)->get();
    }
}
