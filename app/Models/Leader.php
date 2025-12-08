<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leader extends Model
{
    protected $table = 'leader_line_assignments';

    protected $fillable = [
        'user_id',
        'line_id',
        'created_by',
        'updated_by'
    ];

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function line()
    {
        return $this->belongsTo(Line::class, 'line_id', 'id');
    }

    function userCreated()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    function userUpdated()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
