<?php

namespace App\Http\Controllers;

use App\Services\Activity\ActivityServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{

    /**
     * Service Activity 
     */

    protected $activityService;

    public function __construct(ActivityServiceInterface $activityService)
    {
        $this->activityService = $activityService;
    }


    /**
     * Display a activities by users.
     */
    public function getActivitiesByUser()
    {
        $userId = Auth::id();
        $activities = $this->activityService->getAllActivity();
        return $activities;
    }

}
