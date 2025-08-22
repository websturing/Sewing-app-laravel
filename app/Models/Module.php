<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperModule
 */
class Module extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'icon', 'order', 'is_active'];
    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function permissions()
    {
        return $this->hasMany(ModulePermission::class);
    }
    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id');
    }
}
