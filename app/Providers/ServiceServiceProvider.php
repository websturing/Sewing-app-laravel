<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Permissions\PermissionServiceInterface;
use App\Services\Permissions\PermisssionService;

class ServiceServiceProvider extends ServiceProvider
{
    public function register()
    {
        // AUTO-BINDINGS BELOW
        $this->app->bind(\App\Services\User\UserServiceInterface::class, \App\Services\User\UserService::class);
        $this->app->bind(PermissionServiceInterface::class, PermisssionService::class);
    }

    public function boot() {}
}
