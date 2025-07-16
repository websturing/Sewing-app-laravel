<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Permissions\PermissionServiceInterface;
use App\Services\Permissions\PermissionService;

class ServiceServiceProvider extends ServiceProvider
{
    public function register()
    {
        // AUTO-BINDINGS BELOW
        $this->app->bind(\App\Services\modulepermission\ModulepermissionServiceInterface::class, \App\Services\modulepermission\ModulepermissionService::class);
        $this->app->bind(\App\Services\module\ModuleServiceInterface::class, \App\Services\module\ModuleService::class);
        $this->app->bind(\App\Services\role\RoleServiceInterface::class, \App\Services\role\RoleService::class);

        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
    }

    public function boot() {}
}
