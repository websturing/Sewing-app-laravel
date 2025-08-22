<?php

namespace App\Models\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait Auditable
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->logOnly(['*']) // log semua perubahan field
            ->useLogName(strtolower(class_basename($this))) // contoh: "user", "post"
            ->setDescriptionForEvent(fn(string $eventName) => class_basename($this) . " has been {$eventName}");
    }
}
