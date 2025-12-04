<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ReplacementRequestNote extends Model
{
    protected $table = 'replacement_request_note';
    protected $fillable = [
        'replacement_request_id',
        'created_by',
        'description'
    ];

    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at ? $this->created_at->format('F d, Y H:i') : null,
        );
    }

    protected function formattedUpdatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->updated_at ? $this->updated_at->format('F d, Y H:i') : null,
        );
    }

    function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
