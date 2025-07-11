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
        $this->app->bind(\App\Services\Role\RoleServiceInterface::class, \App\Services\Role\RoleService::class);

        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
    }

    public function boot() {}
}
