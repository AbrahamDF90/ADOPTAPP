<?php

use App\Models\Notification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// HU-25: Purga diaria de notificaciones con más de 90 días de antigüedad.
Schedule::command('model:prune', ['--model' => [Notification::class]])
    ->daily()
    ->onOneServer()
    ->withoutOverlapping();
