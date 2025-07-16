<?php

namespace App\Services\modulepermission;

use App\Services\modulepermission\ModulepermissionServiceInterface;
use App\Repositories\modulepermission\ModulepermissionRepositoryInterface;

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
