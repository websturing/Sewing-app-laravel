<?php

namespace App\Services\Permissions;

use App\Repositories\Permissions\PermissionRepositoryInterface;
use App\Services\Permissions\PermissionServiceInterface;
use Illuminate\Support\Collection;

class PermissionService implements PermissionServiceInterface
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {}

    public function getAllPermissions(): Collection
    {
        return $this->permissionRepository->all();
    }

    public function getStructuredMenuForUser($user): Collection
    {
        $allModules = $this->permissionRepository->getAllModulesWithPermissions();
        $userPermissions = $user->getAllPermissions()->pluck('name');

        return $this->buildNestedModules($allModules, $userPermissions);
    }

    private function buildNestedModules(Collection $modules, Collection $userPermissions, $parentId = null): Collection
    {
        return $modules->where('parent_id', $parentId)->map(function ($module) use ($modules, $userPermissions) {
            // Ambil permission module yang dimiliki user
            $filteredPermissions = $module->permissions
                ->filter(fn($perm) => $userPermissions->contains($perm->permission_name))
                ->map(fn($perm) => [
                    'action' => $perm->action,
                    'permission_name' => $perm->permission_name,
                ])
                ->values();

            // Apakah module ini layak tampil?
            $hasViewPermission = $filteredPermissions;

            $children = $this->buildNestedModules($modules, $userPermissions, $module->id);

            if (!$hasViewPermission && $children->isEmpty()) {
                return null;
            }

            return [
                'id' => $module->id,
                'name' => $module->name,
                'slug' => $module->slug,
                'permissions' => $filteredPermissions,
                'children' => $children,
            ];
        })->filter()->values();
    }
}
