<?php

namespace MatthiasWilbrink\FeatureToggle\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MatthiasWilbrink\FeatureToggle\Commands\InsertNewFeaturesCommand;
use MatthiasWilbrink\FeatureToggle\Facades\Feature;

class FeatureToggleServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__.'/../Migrations');
        $this->publishes([
            __DIR__.'/../Config/features.php' => config_path('features.php'),
        ], 'config');

        $this->bootBlade();

        $this->bootCommands();
    }

    public function register()
    {

    }

    private function bootBlade()
    {
        Blade::if('feature', function ($name) {
            return Feature::isOn($name);
        });
    }

    private function bootCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InsertNewFeaturesCommand::class,
            ]);
        }
    }
}