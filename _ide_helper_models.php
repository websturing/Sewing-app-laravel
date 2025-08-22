<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $attendance_date
 * @property string|null $check_in_time
 * @property string|null $check_out_time
 * @property string|null $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AttendanceLog> $logs
 * @property-read int|null $logs_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereAttendanceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckInTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckOutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAttendance {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $attendance_id
 * @property string $log_type
 * @property string $timestamp
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $accuracy
 * @property string|null $device_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereAttendanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereLogType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttendanceLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAttendanceLog {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $employee_code
 * @property string|null $position
 * @property string|null $department
 * @property string|null $join_date
 * @property bool $active
 * @property string|null $device_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmployeeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereJoinDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperEmployee {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property string|null $icon name icon from library Nucleo
 * @property int $order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Module> $children
 * @property-read int|null $children_count
 * @property-read Module|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ModulePermission> $permissions
 * @property-read int|null $permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperModule {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $module_id
 * @property string $action
 * @property string $permission_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Module $module
 * @property-read \Spatie\Permission\Models\Permission|null $permission
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModulePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModulePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModulePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModulePermission whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModulePermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModulePermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModulePermission whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModulePermission wherePermissionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModulePermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperModulePermission {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rolepermission> $permissions
 * @property-read int|null $permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRole {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleSpatie withoutPermission($permissions)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRoleSpatie {}
}

namespace App\Models{
/**
 * @property int $permission_id
 * @property int $role_id
 * @property-read \Spatie\Permission\Models\Permission $permissions
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rolepermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rolepermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rolepermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rolepermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rolepermission whereRoleId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRolepermission {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSetting {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 * @property string $tolerance
 * @property bool $is_night_shift
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserShiftAssignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read array $tolerance_break_down_raw
 * @property-read array $tolerance_breakdown
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereIsNightShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereTolerance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperShift {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestingGa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestingGa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestingGa query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTestingGa {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserShiftAssignment> $shiftAssignments
 * @property-read int|null $shift_assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $shift_id
 * @property string $effective_date_start
 * @property string|null $effective_date_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Shift $shift
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment whereEffectiveDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment whereEffectiveDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShiftAssignment whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUserShiftAssignment {}
}

