<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\StorageSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('site_settings')) {
            view()->share('siteSettings', SiteSetting::getSettings());
        } else {
            view()->share('siteSettings', null);
        }

        if (Schema::hasTable('storage_settings')) {
            $storageSettings = StorageSetting::getSettings();
            $storageSettings->applyToConfig();
            view()->share('storageSettings', $storageSettings);
        } else {
            view()->share('storageSettings', null);
        }
    }
}
