<?php

namespace MatthiasWilbrink\FeatureToggle\Commands;

use Illuminate\Console\Command;
use MatthiasWilbrink\FeatureToggle\Exceptions\FeatureNotFoundException;
use MatthiasWilbrink\FeatureToggle\Exceptions\NoFeaturesException;
use MatthiasWilbrink\FeatureToggle\Exceptions\NoToggableFeaturesException;
use MatthiasWilbrink\FeatureToggle\Facades\Feature;
use MatthiasWilbrink\FeatureToggle\Models\Feature as FeatureModel;

class EnableFeatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:enable';

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

        $this->call('feature:list');

        try {
            $featureName = $this->choice('Which feature would you like to turn on?', Feature::options(FeatureModel::OFF));

            if (Feature::enable($featureName) !== null) {

                $this->info("Turned feature {$featureName} on");
                $this->call('feature:clear-cache');
                return;
            }

            $this->info("Feature {$featureName} already on");

        } catch (FeatureNotFoundException $exception) {
            $this->warn("Feature {$exception->featureName} could not be found");
        } catch (NoFeaturesException $exception) {
            $this->warn('Please define features in the config file');
        }catch (NoToggableFeaturesException $exception){

        }
    }
}