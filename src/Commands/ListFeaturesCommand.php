<?php

namespace MatthiasWilbrink\FeatureToggle\Commands;

use Illuminate\Console\Command;
use MatthiasWilbrink\FeatureToggle\Facades\Feature;

class ListFeaturesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all known features';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        $this->call('feature:create');

        $featureArray = Feature::allArray();

        if (empty($featureArray)) {
            $this->warn('Please define features in the config file');
        } else {
            $this->table(['id', 'name', 'state', 'created at', 'updated at'], $featureArray);
        }

    }
}