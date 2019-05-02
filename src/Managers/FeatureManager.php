<?php

namespace MatthiasWilbrink\FeatureToggle\Managers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use MatthiasWilbrink\FeatureToggle\Exceptions\FeatureNotFoundException;
use MatthiasWilbrink\FeatureToggle\Exceptions\NoFeaturesException;
use MatthiasWilbrink\FeatureToggle\Exceptions\NoToggableFeaturesException;
use MatthiasWilbrink\FeatureToggle\Models\Feature;

/**
 * Class FeatureManager
 * @package MatthiasWilbrink\FeatureToggle\Managers
 */
class FeatureManager
{

    private const CACHE_NAME = 'feature-toggles';
    private const CACHE_TTL = 600;

    /**
     * Check if a feature is enabled
     *
     * @param string $name
     * @return bool
     */
    public function isEnabled(string $name): bool
    {
        $feature = $this->getFeature($name);

        return $feature ? $feature->state : $this->default();
    }

    /**
     * Check if a feature is enabled
     *
     * isOn is an alias of isEnabled
     *
     * @param string $name
     * @return bool
     */
    public function isOn(string $name): bool
    {
        return $this->isEnabled($name);
    }

    /**
     * Enable a feature
     *
     * @param string $name
     * @return Feature|null
     * @throws FeatureNotFoundException
     */
    public function enable(string $name)
    {
        $this->updateCache();

        $feature = $this->getFeature($name);
        if ($feature === null) {
            throw new FeatureNotFoundException($name);
        }
        if ($feature->state === Feature::ON) {
            return null;
        }
        $feature->state = Feature::ON;
        $feature->save();

        $this->updateCache();

        return $feature;
    }

    /**
     * Disable a feature
     *
     * @param string $name
     * @return Feature|null
     * @throws FeatureNotFoundException
     */
    public function disable(string $name)
    {
        $this->updateCache();

        $feature = $this->getFeature($name);
        if ($feature === null) {
            throw new FeatureNotFoundException($name);
        }
        if ($feature->state === Feature::OFF) {
            return null;
        }
        $feature->state = Feature::OFF;
        $feature->save();

        $this->updateCache();

        return $feature;
    }

    /**
     * Create a feature
     *
     * @param string $name
     * @param int $initialState
     * @return Feature|null
     */
    public function createFeature(string $name, int $initialState)
    {
        $this->updateCache();

        $feature = $this->getFeature($name);
        if ($feature !== null) {
            return null;
        }
        $feature = new Feature();
        $feature->name = $name;
        $feature->state = $initialState;
        $feature->save();
        return $feature;
    }

    /**
     * Get all features as array
     *
     * @return array
     */
    public function allArray()
    {
        return $this->all()->map(function ($feature) {
            $feature->state = $feature->stateLabel;
            return $feature;
        })->toArray();
    }

    /**
     * Get features as options for commands
     * @param int $filter
     * @return array
     * @throws NoFeaturesException
     * @throws NoToggableFeaturesException
     */
    public function options(int $filter)
    {
        $options = $this->all();
        if ($options->isNotEmpty()) {
            $options = $options->where('state', $filter);
            if ($options->isNotEmpty()) {
                return $options->pluck('name')->toArray();
            }
            throw new NoToggableFeaturesException();
        }
        throw new NoFeaturesException();
    }

    /**
     * Check if the feature cache exists
     *
     * @return bool
     */
    public function cacheExists(): bool
    {
        return Cache::has(self::CACHE_NAME);
    }

    /**
     * Clear the feature cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_NAME);
    }

    /**
     * Return the default state
     *
     * @return bool
     */
    private function default(): bool
    {
        return Config::get('features.default_behaviour', Feature::OFF);
    }

    /**
     * Get all features as collection
     *
     * @return \Illuminate\Database\Eloquent\Collection|Feature[]
     */
    private function all()
    {
        return Feature::all();
    }

    /**
     * Get feature by name if it exists
     *
     * @param string $name
     * @return Feature|null
     */
    private function getFeature(string $name)
    {
        $useCache = Config::get('features.feature_caching', false);

        if ($useCache) {
            return $this->getFeaturesCache()
                ->where('name', $name)
                ->first();
        }

        return Feature::where('name', $name)
            ->first();
    }

    /**
     * Get features from cache
     *
     * @return mixed
     */
    private function getFeaturesCache()
    {
        if (!$this->cacheExists()) {
            $values = $this->all();
            // Note ttl does not work on file based caching.
            Cache::put(self::CACHE_NAME, $values, self::CACHE_TTL);
        }

        return Cache::get(self::CACHE_NAME);
    }

    /**
     * Clear and then fill the cache
     *
     * @return void
     */
    private function updateCache(): void
    {
        $this->clearCache();

        if (Config::get('features.feature_caching', false)) {
            $this->getFeaturesCache();
        }
    }

}