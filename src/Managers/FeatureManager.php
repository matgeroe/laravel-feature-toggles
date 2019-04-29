<?php

namespace MatthiasWilbrink\FeatureToggle\Managers;

use Illuminate\Support\Facades\Config;
use MatthiasWilbrink\FeatureToggle\Models\Feature;

class FeatureManager
{
    public function isOn(string $name)
    {
        $feature = $this->getFeature($name);

        return $feature ? $feature->state : $this->default();
    }

    public function default()
    {
        return Config::get('features.default_behaviour', Feature::OFF);
    }

    public function turnOn(string $name)
    {
        $feature = $this->getFeature($name);
        if ($feature === null) {
            return;
        }
        $feature->state = Feature::ON;
        $feature->save();
    }

    public function turnOff(string $name)
    {
        $feature = $this->getFeature($name);
        if ($feature === null) {
            return;
        }
        $feature->state = Feature::OFF;
        $feature->save();
    }

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

    private function getFeature(string $name)
    {
        return Feature::where('name', $name)->first();
    }
}