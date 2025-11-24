<?php

namespace App\Providers;

use App\Services\DataForSeo\DataForSeoClientInterface;
use App\Services\DataForSeo\FakeDataForSeoClient;
use Illuminate\Support\ServiceProvider;

class DataForSeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            DataForSeoClientInterface::class,
            FakeDataForSeoClient::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
