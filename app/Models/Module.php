<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];
    public function permissions()
    {
        return $this->hasMany(ModulePermission::class);
    }
}
