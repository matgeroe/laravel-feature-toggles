<?php

namespace MatthiasWilbrink\FeatureToggle\Commands;

use Illuminate\Console\Command;
use MatthiasWilbrink\FeatureToggle\Exceptions\FeatureNotFoundException;
use MatthiasWilbrink\FeatureToggle\Facades\Feature;

class EnableFeatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:enable {name : Feature name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn a feature on';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $featureName = $this->argument('name');

            if (Feature::enable($featureName) !== null) {

                $this->info("Turned feature {$featureName} on");
                return;
            }

            $this->info("Feature {$featureName} already on");

        } catch (FeatureNotFoundException $exception) {
            $this->warn("Feature {$exception->featureName} could not be found");
        }
    }
}