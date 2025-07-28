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
        $this->app->bind(\App\Services\Employee\EmployeeServiceInterface::class, \App\Services\Employee\EmployeeService::class);
        $this->app->bind(\App\Services\Shift\ShiftServiceInterface::class, \App\Services\Shift\ShiftService::class);

        $this->app->bind(\App\Services\User\UserServiceInterface::class, \App\Services\User\UserService::class);
        $this->app->bind(\App\Services\Rolepermission\RolepermissionServiceInterface::class, \App\Services\Rolepermission\RolepermissionService::class);
        $this->app->bind(\App\Services\modulepermission\ModulepermissionServiceInterface::class, \App\Services\modulepermission\ModulepermissionService::class);
        $this->app->bind(\App\Services\module\ModuleServiceInterface::class, \App\Services\module\ModuleService::class);
        $this->app->bind(\App\Services\Role\RoleServiceInterface::class, \App\Services\Role\RoleService::class);

        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
    }

    public function boot() {}
}
