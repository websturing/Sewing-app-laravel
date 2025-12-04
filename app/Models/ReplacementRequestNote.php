<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplacementRequestNote extends Model
{
    protected $table = 'replacement_request_note';
    protected $fillable = [
        'replacement_request_id',
        'created_by',
        'description'
    ];
}
