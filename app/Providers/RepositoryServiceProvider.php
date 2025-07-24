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
        $this->app->bind(\App\Repositories\Shift_\Shift_RepositoryInterface::class, \App\Repositories\Shift_\Shift_Repository::class);
        $this->app->bind(\App\Repositories\Line\LineRepositoryInterface::class, \App\Repositories\Line\LineRepository::class);
        $this->app->bind(\App\Repositories\User\UserRepositoryInterface::class, \App\Repositories\User\UserRepository::class);
        $this->app->bind(\App\Repositories\Rolepermission\RolepermissionRepositoryInterface::class, \App\Repositories\Rolepermission\RolepermissionRepository::class);
        $this->app->bind(\App\Repositories\modulepermission\ModulepermissionRepositoryInterface::class, \App\Repositories\modulepermission\ModulepermissionRepository::class);
        $this->app->bind(\App\Repositories\module\ModuleRepositoryInterface::class, \App\Repositories\module\ModuleRepository::class);
        $this->app->bind(\App\Repositories\Role\RoleRepositoryInterface::class, \App\Repositories\Role\RoleRepository::class);

        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    public function boot() {}
}
