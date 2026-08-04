<?php

use App\Console\Commands\AutoFinalizeClosing;
use App\Console\Commands\AutoFinalizeClosingBulanan;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('store:auto-finalize')->dailyAt('00:05');
Schedule::command('store:auto-finalize-bulanan')->dailyAt('00:10');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
