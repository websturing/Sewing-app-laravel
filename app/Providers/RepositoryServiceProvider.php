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
        $this->app->bind(\App\Repositories\Jokowi\JokowiRepositoryInterface::class, \App\Repositories\Jokowi\JokowiRepository::class);
        $this->app->bind(\App\Repositories\Product\ProductRepositoryInterface::class, \App\Repositories\Product\ProductRepository::class);
        $this->app->bind(\App\Repositories\User\UserRepositoryInterface::class, \App\Repositories\User\UserRepository::class);
        $this->app->bind(\App\Repositories\Role\RoleRepositoryInterface::class, \App\Repositories\Role\RoleRepository::class);

        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    public function boot() {}
}
