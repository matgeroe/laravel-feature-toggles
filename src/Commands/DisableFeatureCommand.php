<?php

namespace MatthiasWilbrink\FeatureToggle\Commands;

use Illuminate\Console\Command;
use MatthiasWilbrink\FeatureToggle\Exceptions\FeatureNotFoundException;
use MatthiasWilbrink\FeatureToggle\Exceptions\NoFeaturesException;
use MatthiasWilbrink\FeatureToggle\Exceptions\NoToggableFeaturesException;
use MatthiasWilbrink\FeatureToggle\Facades\Feature;
use MatthiasWilbrink\FeatureToggle\Models\Feature as FeatureModel;

class DisableFeatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn a feature off';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        $this->call('feature:list');

        try {
            $featureName = $this->choice('Which feature would you like to turn off?', Feature::options(FeatureModel::ON));

            if (Feature::disable($featureName) !== null) {

                $this->info("Turned feature {$featureName} off");
                return;
            }

            $this->info("Feature {$featureName} already off");

        } catch (FeatureNotFoundException $exception) {
            $this->warn("Feature {$exception->featureName} could not be found");
        } catch (NoFeaturesException $exception) {
            $this->warn('Please define features in the config file');
        } catch (NoToggableFeaturesException $exception){

        }

    }
}