<?php

namespace App\Services\Modulepermission;

use App\Services\Modulepermission\ModulepermissionServiceInterface;
use App\Repositories\Modulepermission\ModulepermissionRepositoryInterface;

class ModulepermissionService implements ModulepermissionServiceInterface
{
    protected $modulepermissionRepository;

    public function __construct(ModulepermissionRepositoryInterface $modulepermissionRepository)
    {
        $this->modulepermissionRepository = $modulepermissionRepository;
    }

    public function getAllModulepermission()
    {
        return $this->modulepermissionRepository->all();
    }
    /**
     * Bulk create module permissions.
     *
     * @param array $data
     * @return mixed
     */
    public function bulkCreateModulepermission(array $data)
    {
        return $this->modulepermissionRepository->bulkCreate($data);
    }
}
