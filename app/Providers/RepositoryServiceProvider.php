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
        $this->app->bind(\App\Repositories\modulepermission\ModulepermissionRepositoryInterface::class, \App\Repositories\modulepermission\ModulepermissionRepository::class);
        $this->app->bind(\App\Repositories\module\ModuleRepositoryInterface::class, \App\Repositories\module\ModuleRepository::class);
        $this->app->bind(\App\Repositories\Role\RoleRepositoryInterface::class, \App\Repositories\Role\RoleRepository::class);

        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    public function boot() {}
}
