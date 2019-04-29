<?php

namespace MatthiasWilbrink\FeatureToggle\Managers;

use Illuminate\Support\Facades\Config;
use MatthiasWilbrink\FeatureToggle\Exceptions\FeatureNotFoundException;
use MatthiasWilbrink\FeatureToggle\Models\Feature;

/**
 * Class FeatureManager
 * @package MatthiasWilbrink\FeatureToggle\Managers
 */
class FeatureManager
{
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
     * Return the default state
     *
     * @return bool
     */
    public function default(): bool
    {
        return Config::get('features.default_behaviour', Feature::OFF);
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
        $feature = $this->getFeature($name);
        if ($feature === null) {
            throw new FeatureNotFoundException($name);
        }
        if ($feature->state === Feature::ON) {
            return null;
        }
        $feature->state = Feature::ON;
        $feature->save();
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
        $feature = $this->getFeature($name);
        if ($feature === null) {
            throw new FeatureNotFoundException($name);
        }
        if ($feature->state === Feature::OFF) {
            return null;
        }
        $feature->state = Feature::OFF;
        $feature->save();
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
     * Get all features as collection
     *
     * @return \Illuminate\Database\Eloquent\Collection|Feature[]
     */
    public function all()
    {
        return Feature::all();
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
     * Get feature by name if it exists
     *
     * @param string $name
     * @return Feature|null
     */
    private function getFeature(string $name)
    {
        return Feature::where('name', $name)->first();
    }
}