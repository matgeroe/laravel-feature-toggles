<?php

use MatthiasWilbrink\FeatureToggle\Models\Feature;

return [

    /*
    |--------------------------------------------------------------------------
    | Available features
    |--------------------------------------------------------------------------
    |
    | All features must be registered here,
    | together with their default state.
    |
    */

    'features' => [
        'demo_feature' => Feature::OFF,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default behaviour
    |--------------------------------------------------------------------------
    |
    | This defines the state a feature will assume
    | when it is not defined in the array above.
    |
    */

    'default_behaviour' => Feature::OFF,
];