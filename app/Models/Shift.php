<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @mixin IdeHelperShift
 */
class Shift extends Model
{
    protected $fillable = ['name', 'start_time', 'end_time', 'tolerance', 'is_night_shift'];

    protected $casts = [
        'is_night_shift' => 'boolean',
    ];


    public function assignments()
    {
        return $this->hasMany(UserShiftAssignment::class, 'shift_id');
    }


    public function getToleranceBreakDownRawAttribute(): array
    {
        [$hours, $minutes, $seconds] = array_pad(explode(':', $this->tolerance), 3, 0);

        return [
            'hours' => (int)$hours,
            'minutes' => (int)$minutes,
            'seconds' => (int)$seconds,
        ];
    }

    public function getToleranceBreakdownAttribute(): array
    {
        [$hours, $minutes, $seconds] = array_pad(explode(':', $this->tolerance), 3, 0);

        $hours = (int)$hours;
        $minutes = (int)$minutes;
        $seconds = (int)$seconds;

        if ($hours > 0 && $minutes === 0 && $seconds === 0) {
            return [
                'value' => $hours,
                'unit' => 'hour',
                'label' => "$hours hour" . ($hours > 1 ? 's' : '')
            ];
        }

        if ($minutes > 0 && $hours === 0 && $seconds === 0) {
            return [
                'value' => $minutes,
                'unit' => 'minute',
                'label' => "$minutes minute" . ($minutes > 1 ? 's' : '')
            ];
        }

        if ($seconds > 0 && $hours === 0 && $minutes === 0) {
            return [
                'value' => $seconds,
                'unit' => 'second',
                'label' => "$seconds second" . ($seconds > 1 ? 's' : '')
            ];
        }

        // Jika kombinasi (ambil yang terbesar)
        if ($hours > 0) {
            return [
                'value' => $hours,
                'unit' => 'hour',
                'label' => "$hours hour" . ($hours > 1 ? 's' : '')
            ];
        }

        if ($minutes > 0) {
            return [
                'value' => $minutes,
                'unit' => 'minute',
                'label' => "$minutes minute" . ($minutes > 1 ? 's' : '')
            ];
        }

        return [
            'value' => $seconds,
            'unit' => 'second',
            'label' => "$seconds second" . ($seconds > 1 ? 's' : '')
        ];
    }
}
