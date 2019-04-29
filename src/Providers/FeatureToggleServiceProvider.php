<?php

namespace MatthiasWilbrink\FeatureToggle\Providers;

use Illuminate\Support\ServiceProvider;

class FeatureToggleServiceProvider extends ServiceProvider
{


    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Migration');
        $this->publishes([
            __DIR__.'/../Config/features.php' => config_path('package.php'),
        ], 'config');
    }

    public function register()
    {

    }
}