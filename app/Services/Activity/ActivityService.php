<?php

namespace App\Services\Activity;

use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class ActivityService implements ActivityServiceInterface
{
    protected $activityRepository;

    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function getAllActivity(array $filters)
    {
        $query = $this->activityRepository->query()->with('causer');

        $query->when(
            $filters['user_id'] ?? null,
            fn($q, $userId) =>
            $q->where('causer_id', $userId)
        );

        $query->when(
            $filters['log_name'] ?? null,
            fn($q, $log_name) =>
            $q->where('log_name', 'LIKE', "%{$log_name}%")
        );

        $query->when(
            $filters['name'] ?? null,
            fn($q, $name) =>
            $q->where('description', 'LIKE', "%{$name}%")
        );

        $query->when(
            $filters['month'] ?? null,
            fn($q, $month) =>
            $q->whereMonth('created_at', $month)
        );

        $query->when(
            $filters['year'] ?? null,
            fn($q, $year) =>
            $q->whereYear('created_at', $year)
        );

        $query->when(($filters['date_from'] ?? null) && ($filters['date_to'] ?? null),
            fn($q) =>
            $q->whereBetween('created_at', [
                Carbon::parse($filters['date_from'])->startOfDay(),
                Carbon::parse($filters['date_to'])->endOfDay(),
            ])
        );


        $sortField = $filters['sort'] ?? 'created_at';
        $sortDir   = $filters['direction'] ?? 'desc';

        // Pagination
        $perPage = $filters['per_page'] ?? 20;

        return $query->orderBy($sortField, $sortDir)->paginate($perPage);
    }

    public function getActivitiesByUser(int $id): collection
    {
        $activitiesUser = $this->activityRepository->getByUser($id);
        return $activitiesUser->sortByDesc('id');
    }
}
