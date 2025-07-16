<?php

namespace App\Services\Module;

use App\Services\Module\ModuleServiceInterface;
use App\Repositories\Module\ModuleRepositoryInterface;

class ModuleService implements ModuleServiceInterface
{
    protected $moduleRepository;

    public function __construct(ModuleRepositoryInterface $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function getAllModule()
    {
        return $this->moduleRepository->all();
    }

    public function updateOrCreateModule(array $data)
    {
        return $this->moduleRepository->updateOrCreate($data);
    }

    public function createModule(array $data)
    {
        return $this->moduleRepository->create($data);
    }
}
