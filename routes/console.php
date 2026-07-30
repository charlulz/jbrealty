<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| FlexMLS daily property sync
|--------------------------------------------------------------------------
|
| Imports/updates Jeremiah Brown listings + photos from the ImagineMLS
| own-data Spark feed. Requires a server cron that runs:
|   * * * * * php /path/to/artisan schedule:run
|
*/
Schedule::command('properties:sync-all')
    ->dailyAt('05:00')
    ->timezone('America/New_York')
    ->withoutOverlapping()
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/flexmls-sync.log'));
