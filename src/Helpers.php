<?php

use MatthiasWilbrink\FeatureToggle\Managers\FeatureManager;

if (!function_exists('feature')) {
    function feature(string $name): bool
    {
        return (app(FeatureManager::class))->isEnabled($name);
    }
}