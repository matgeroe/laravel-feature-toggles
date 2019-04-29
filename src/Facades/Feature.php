<?php

namespace MatthiasWilbrink\FeatureToggle\Facades;

use Illuminate\Support\Facades\Facade;
use MatthiasWilbrink\FeatureToggle\Managers\FeatureManager;

/**
 * Class Feature
 *
 * @package MatthiasWilbrink\FeatureToggle\Facades
 */
class Feature extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return FeatureManager::class;
    }
}