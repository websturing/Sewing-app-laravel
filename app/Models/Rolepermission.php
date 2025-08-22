<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

/**
 * @mixin IdeHelperRolepermission
 */
class Rolepermission extends Model
{

    protected $table = 'role_has_permissions';
    // protected $primaryKey = null;
    public $timestamps = false;
    protected $fillable = [
        'role_id',
        'permission_id'
        // tambahkan kolom lain yang boleh diisi
    ];

    public function permissions()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
