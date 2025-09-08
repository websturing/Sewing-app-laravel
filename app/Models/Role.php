<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Auditable;

/**
 * @mixin IdeHelperRole
 */
class Role extends Model
{
    use Auditable;
    protected $fillable = ['name', 'guard_name', 'color'];

    public function permissions()
    {
        return $this->hasMany(Rolepermission::class, 'role_id');
    }
}
