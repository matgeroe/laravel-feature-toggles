<?php

namespace MatthiasWilbrink\FeatureToggle\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MatthiasWilbrink\FeatureToggle\Commands\ClearCacheCommand;
use MatthiasWilbrink\FeatureToggle\Commands\DisableFeatureCommand;
use MatthiasWilbrink\FeatureToggle\Commands\EnableFeatureCommand;
use MatthiasWilbrink\FeatureToggle\Commands\InsertNewFeaturesCommand;
use MatthiasWilbrink\FeatureToggle\Commands\ListFeaturesCommand;
use MatthiasWilbrink\FeatureToggle\Managers\FeatureManager;

class FeatureToggleServiceProvider extends ServiceProvider
{
    /**
     * Boot the Feature Service Provider
     */
    public function boot()
    {
        $this->publishMigrations();
        $this->publishConfig();
        $this->bootBlade();
        $this->bootCommands();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Add migrations
     */
    private function publishMigrations()
    {
        $this->publishes([
            __DIR__.'/../Migrations/' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Add config
     */
    private function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/features.php' => config_path('features.php'),
        ], 'config');
    }

    /**
     * Add blade directive
     */
    private function bootBlade()
    {
        Blade::if('feature', function ($name) {
            return (app(FeatureManager::class))->isEnabled($name);
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
                ClearCacheCommand::class,
            ]);
        }
    }

    /**
     * Merge config
     */
    private function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/features.php', 'features'
        );
    }
}