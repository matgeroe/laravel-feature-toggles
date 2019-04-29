<?php
if (! function_exists('feature')) {
    function feature(string $name): bool
    {
        return (app(\MatthiasWilbrink\FeatureToggle\Managers\FeatureManager::class))->isOn($name);
    }
}