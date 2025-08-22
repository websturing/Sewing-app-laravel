<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;


/**
 * @mixin IdeHelperModulePermission
 */
class ModulePermission extends Model
{
    protected $fillable = ['module_id', 'action', 'permission_name'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_name', 'name');
    }
}
