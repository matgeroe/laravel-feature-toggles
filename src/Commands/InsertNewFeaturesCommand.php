<?php

namespace MatthiasWilbrink\FeatureToggle\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use MatthiasWilbrink\FeatureToggle\Facades\Feature;

class InsertNewFeaturesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description= 'Reads features from config, and inserts them in the database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        collect(Config::get('features.features', null))
            ->each(function (int $default, string $feature ) {
                if (Feature::createFeature($feature, $default) !== null) {
                    $this->info("Feature created: {$feature}.");
                } else {
                    $this->info("Feature already exists: {$feature}.");
                }
            });
    }
}