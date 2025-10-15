<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule monthly progress reset on the 1st of each month at 00:00
Schedule::command('progress:reset-monthly')
    ->monthly()
    ->at('00:00')
    ->description('Reset all site progress to "Belum Visit" monthly')
    ->withoutOverlapping();
