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


    public function getStructuredMenuForUser($user)
    {
        $allModules      = $this->permissionRepository->getAllModulesWithPermissions();
        $userPermissions = $user->getAllPermissions()->pluck('name'); // Collection

        $menu = $this->buildNestedModules($allModules, $userPermissions); // Collection
        return $this->filterMenu($menu, $userPermissions); // aman (Collection)
    }

    private function filterMenu(array|Collection $menus, array|Collection $userPermissions): array
    {
        $userPermissions = collect($userPermissions)->toArray();

        return collect($menus)
            ->map(function ($menu) use ($userPermissions) {
                // children bisa Collection/array/null → normalisasi
                $children = $this->filterMenu(collect(data_get($menu, 'children', [])), $userPermissions);

                $hasReadPermission = collect(data_get($menu, 'permissions', []))
                    ->pluck('permission_name')
                    ->intersect($userPermissions)
                    ->filter(fn($perm) => str_ends_with($perm, '.read'))
                    ->isNotEmpty();

                if ($hasReadPermission || !empty($children)) {
                    $menu['children'] = $children;
                    return $menu;
                }
                return null;
            })
            ->filter()
            ->values()
            ->toArray();
    }

    private function buildNestedModules(Collection $modules, Collection $userPermissions, $parentId = null): Collection
    {
        return $modules->where('parent_id', $parentId)
            ->sortBy('order')
            ->map(function ($module) use ($modules, $userPermissions) {
                $filteredPermissions = $module->permissions
                    ->filter(fn($perm) => $userPermissions->contains($perm->permission_name))
                    ->map(fn($perm) => [
                        'action' => $perm->action,
                        'permission_name' => $perm->permission_name,
                    ])
                    ->values();

                $children = $this->buildNestedModules($modules, $userPermissions, $module->id);

                // boolean yang jelas, bukan Collection truthy
                if ($filteredPermissions->isEmpty() && $children->isEmpty()) {
                    return null;
                }

                return [
                    'id'          => $module->id,
                    'name'        => $module->name,
                    'slug'        => $module->slug,
                    'icon'        => $module->icon,
                    'permissions' => $filteredPermissions->toArray(), // konsisten array
                    'children'    => $children, // biarkan Collection, filterMenu handle
                ];
            })
            ->filter()
            ->values();
    }



    /** MODULE PERMISSION */
    public function getModulePermission(): Collection
    {
        return $this->permissionRepository->getModulePermission();
    }
}
