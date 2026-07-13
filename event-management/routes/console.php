<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/**
 *  This file is where you may define all of your Closure based console commands. Each Closure is bound to a command instance allowing a simple approach to interacting with each command's IO methods.
 */

Artisan::command('inspire', fn () => $this->comment(Inspiring::quote()))->purpose('Display an inspiring quote');

// Run daily at 8:00 AM
Schedule::command('events:send-reminders')->dailyAt('08:00');

// Cron Job:
//Schedule::command('events:send-reminders')->cron('0 8 * * *');

// Or schedule a closure directly, no command class needed:
//Schedule::call(function () { // cleanup logic, etc. })->hourly();
