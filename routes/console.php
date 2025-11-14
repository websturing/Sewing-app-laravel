<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('sync:cutting-summary')
    ->hourly()
    ->onOneServer()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('sync:glnumbers')
    ->hourly()
    ->onOneServer()
    ->withoutOverlapping()
    ->runInBackground();
