<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Wing5wong\KamarDirectoryServices\Emergency\EmergencyServiceInterface;
use Wing5wong\KamarDirectoryServices\Emergency\ViviEmergencyService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EmergencyServiceInterface::class, ViviEmergencyService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
