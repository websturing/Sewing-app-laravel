<?php

namespace App\Repositories\Activity;

use Spatie\Activitylog\Models\Activity;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function all()
    {
        return Activity::all();
    }
}
