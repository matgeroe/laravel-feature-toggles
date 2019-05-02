<?php

namespace MatthiasWilbrink\FeatureToggle\Commands;

use Illuminate\Console\Command;
use MatthiasWilbrink\FeatureToggle\Facades\Feature;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the feature cache';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Feature::clearCache();

        if (!Feature::cacheExists()) {
            $this->info('Feature cache cleared!');
            return;
        }

        $this->warn('Cache was not cleared');
    }
}