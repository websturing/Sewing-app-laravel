<?php

namespace App\Services\module;

use App\Models\Module;
use App\Services\module\ModuleServiceInterface;
use App\Repositories\module\ModuleRepositoryInterface;
use App\Repositories\modulepermission\ModulepermissionRepositoryInterface;
use App\Enums\PermissionType;
use App\Http\Resources\ModuleResources;

class ModuleService implements ModuleServiceInterface
{
    protected $moduleRepository;
    protected $modulePermissionRepository;

    public function __construct(
        ModuleRepositoryInterface $moduleRepository,
        ModulepermissionRepositoryInterface $modulePermissionRepository
    ) {
        $this->moduleRepository = $moduleRepository;
        $this->modulePermissionRepository = $modulePermissionRepository;
    }

    public function getAllModule()
    {
        $module =  $this->moduleRepository->all();
        return ModuleResources::collection($module);
    }

    public function updateOrCreateModule(array $data)
    {
        return $this->moduleRepository->updateOrCreate($data);
    }

    /** CREATE DATA */
    public function createModule(array $data)
    {
        return $this->moduleRepository->create($data);
    }
    public function createModuleWithPermissions(array $moduleData)
    {
        // 1. Buat Module
        $module = $this->moduleRepository->create($moduleData);

        // 2. Generate permissions berdasarkan slug module
        $permissions = $this->generateDefaultPermissions($module->slug, $module);

        // 3. Bulk insert permissions
        $this->modulePermissionRepository->bulkCreate($permissions);
        return $module;
    }

    /** UPDATE DATA */
    public function updateModuleWithPermissions(int $id, array $data)
    {
        $module =  $this->moduleRepository->update($id, $data);
        $permissions = $this->generateDefaultPermissions($module->slug, $module);
        $this->modulePermissionRepository->bulkUpdate($id, $permissions);
        return $module;
    }


    /** DELETE DATA */
    public function deleteModule(int $id)
    {
        return $this->moduleRepository->delete($id);
    }

    private function generateDefaultPermissions(string $moduleSlug, Module $module): array
    {
        return collect(PermissionType::defaultPermissions())
            ->map(function (PermissionType $type) use ($moduleSlug, $module) {
                return [
                    'module_id' => $module->id,  // Menggunakan $module dari parameter
                    'action' => $type->value,
                    'permission_name' => "{$moduleSlug}.{$type->value}",
                ];
            })
            ->toArray();
    }
}
