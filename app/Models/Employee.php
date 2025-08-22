<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperEmployee
 */
class Employee extends Model
{
    protected $fillable = ['employee_code', 'position', 'department', 'join_date', 'active', 'user_id', 'device_id'];
    protected $casts = [
        'active' => 'boolean'
    ];

    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
