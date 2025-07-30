<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserShiftAssignment extends Model
{


    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
