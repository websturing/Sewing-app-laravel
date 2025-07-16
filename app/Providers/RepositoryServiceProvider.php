<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Permissions\PermissionRepositoryInterface;
use App\Repositories\Permissions\PermissionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // AUTO-BINDINGS BELOW (DO NOT REMOVE)
        $this->app->bind(\App\Repositories\Modulepermission\ModulepermissionRepositoryInterface::class, \App\Repositories\Modulepermission\ModulepermissionRepository::class);
        $this->app->bind(\App\Repositories\Module\ModuleRepositoryInterface::class, \App\Repositories\Module\ModuleRepository::class);
        $this->app->bind(\App\Repositories\Role\RoleRepositoryInterface::class, \App\Repositories\Role\RoleRepository::class);

        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    public function boot() {}
}
