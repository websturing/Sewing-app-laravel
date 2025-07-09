<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    protected $fillable = ['module_id', 'action', 'permission_name'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
