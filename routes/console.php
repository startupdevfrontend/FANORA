<?php

use App\Services\SubscriptionService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Maintenance commands
|--------------------------------------------------------------------------
*/

Artisan::command('fanora:pwa-icons', function () {
    $output = public_path('icons');

    if (! is_dir($output)) {
        mkdir($output);
    }

    $files = app(\App\Services\PwaIconService::class)->generateAll($output);

    foreach (array_keys($files) as $file) {
        $this->info("Generado: {$file}");
    }
})->purpose('Generate the PWA/FANORA icon set');

Artisan::command('fanora:expire-subscriptions', function () {
    $expired = app(SubscriptionService::class)->expireDueSubscriptions();

    $this->info("Assinaturas expiradas: {$expired}");
})->purpose('Expire subscriptions whose period has ended');

/*
|--------------------------------------------------------------------------
| Scheduler
|--------------------------------------------------------------------------
*/

Schedule::daily()->at('04:15')->command('fanora:expire-subscriptions');