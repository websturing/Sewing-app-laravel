<?php

namespace App\Services\Permissions;

use App\Repositories\Permissions\PermissionRepositoryInterface;
use App\Services\Permissions\PermissionServiceInterface;

class PermissionService implements PermissionServiceInterface
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {}

    public function getAllPermissions()
    {
        return $this->permissionRepository->all();
    }

    public function getStructuredMenuForUser($user)
    {
        $modules = $this->permissionRepository->getUserModulesWithPermissions($user);
        return $this->buildNestedModules($modules);
    }

    private function buildNestedModules($modules, $parentId = null)
    {
        return $modules->where('parent_id', $parentId)->map(function ($module) use ($modules) {
            return [
                'id' => $module->id,
                'name' => $module->name,
                'slug' => $module->slug,
                'children' => $this->buildNestedModules($modules, $module->id),
            ];
        })->values();
    }
}
