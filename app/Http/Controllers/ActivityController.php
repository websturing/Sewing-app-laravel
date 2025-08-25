<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
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
     * Get all activities 
     * 
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     * @response array{data: array}
     */
    public function getActivities(Request $request)
    {
        try {
            $activities = $this->activityService->getAllActivity($request->all());

            return ActivityResource::collection($activities);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activities for the authenticated user
     * 
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     * @response array{data: array}
     */
    public function getActivitiesByUser()
    {
        try {
            $userId = Auth::id();
            $activities = $this->activityService->getActivitiesByUser($userId);

            return ActivityResource::collection($activities);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
