<?php

namespace App\Services\Activity;

use App\Repositories\Activity\ActivityRepositoryInterface;

class ActivityService implements ActivityServiceInterface
{
    protected $activityRepository;

    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function getAllActivity()
    {
        return $this->activityRepository->all();
    }
}
