<?php

namespace App\Providers;

use App\Services\Activity\ActivityService;
use App\Services\Activity\ActivityServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\Permissions\PermissionServiceInterface;
use App\Services\Permissions\PermissionService;

class ServiceServiceProvider extends ServiceProvider
{
    public function register()
    {
        // AUTO-BINDINGS BELOW
        $this->app->bind(\App\Services\Attendance\AttendanceServiceInterface::class, \App\Services\Attendance\AttendanceService::class);
        $this->app->bind(\App\Services\Shiftassignment\ShiftassignmentServiceInterface::class, \App\Services\Shiftassignment\ShiftassignmentService::class);
        $this->app->bind(\App\Services\Employee\EmployeeServiceInterface::class, \App\Services\Employee\EmployeeService::class);
        $this->app->bind(\App\Services\Shift\ShiftServiceInterface::class, \App\Services\Shift\ShiftService::class);

        $this->app->bind(\App\Services\User\UserServiceInterface::class, \App\Services\User\UserService::class);
        $this->app->bind(\App\Services\Rolepermission\RolepermissionServiceInterface::class, \App\Services\Rolepermission\RolepermissionService::class);
        $this->app->bind(\App\Services\modulepermission\ModulepermissionServiceInterface::class, \App\Services\modulepermission\ModulepermissionService::class);
        $this->app->bind(\App\Services\module\ModuleServiceInterface::class, \App\Services\module\ModuleService::class);
        $this->app->bind(\App\Services\Role\RoleServiceInterface::class, \App\Services\Role\RoleService::class);
        $this->app->bind(\App\Services\Line\LineServiceInterface::class, \App\Services\Line\LineService::class);
        $this->app->bind(\App\Services\Assigmentline\AssigmentlineServiceInterface::class, \App\Services\Assigmentline\AssigmentlineService::class);
        $this->app->bind(\App\Services\Glnumber\GlnumberServiceInterface::class, \App\Services\Glnumber\GlnumberService::class);
        $this->app->bind(\App\Services\Cutting\CuttingIntegrationServiceInterface::class, \App\Services\Cutting\CuttingIntegrationService::class);
        $this->app->bind(\App\Services\Stockin\StockinServiceInterface::class, \App\Services\Stockin\StockinService::class);
        $this->app->bind(\App\Services\Stockin\StockInSummaryServiceInterface::class, \App\Services\Stockin\StockInSummaryService::class);
        $this->app->bind(\App\Services\Stockin\StockInGroupServiceInterface::class, \App\Services\Stockin\StockInGroupService::class);

        $this->app->bind(\App\Services\CuttingGlnumber\CuttingGlnumberServiceInterface::class, \App\Services\CuttingGlnumber\CuttingGlnumberService::class);
        $this->app->bind(\App\Services\Defect\DefectServiceInterface::class, \App\Services\Defect\DefectService::class);


        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
        $this->app->bind(ActivityServiceInterface::class, ActivityService::class);
    }

    public function boot() {}
}
