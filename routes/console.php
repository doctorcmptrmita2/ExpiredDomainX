<?php

use App\Jobs\FetchDomainsFromProvider;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new FetchDomainsFromProvider(100))
    ->daily()
    ->at('02:00')
    ->name('fetch-domains-from-provider');
