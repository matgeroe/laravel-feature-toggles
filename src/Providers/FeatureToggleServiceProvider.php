<?php

namespace MatthiasWilbrink\FeatureToggle\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MatthiasWilbrink\FeatureToggle\Commands\DisableFeatureCommand;
use MatthiasWilbrink\FeatureToggle\Commands\EnableFeatureCommand;
use MatthiasWilbrink\FeatureToggle\Commands\InsertNewFeaturesCommand;
use MatthiasWilbrink\FeatureToggle\Commands\ListFeaturesCommand;
use MatthiasWilbrink\FeatureToggle\Facades\Feature;

class FeatureToggleServiceProvider extends ServiceProvider
{
    /**
     * Boot the Feature Service Provider
     */
    public function boot()
    {
        $this->bootMigrations();
        $this->bootConfig();
        $this->bootBlade();
        $this->bootCommands();
    }

    /**
     * Add migrations
     */
    private function bootMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }

    /**
     * Add config
     */
    private function bootConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/features.php' => config_path('features.php'),
        ], 'config');
    }

    /**
     * Add blade directive
     */
    private function bootBlade()
    {
        Blade::if('feature', function ($name) {
            return Feature::isEnabled($name);
        });
    }

    /**
     * Add commands
     */
    private function bootCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InsertNewFeaturesCommand::class,
                ListFeaturesCommand::class,
                EnableFeatureCommand::class,
                DisableFeatureCommand::class,
            ]);
        }
    }
}