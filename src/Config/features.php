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
    | Feature caching
    |--------------------------------------------------------------------------
    |
    | Limit the amount of queries that are send to the
    | database by caching the state of all features.
    |
    */

    'feature_caching' => true,

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

    /*
   |--------------------------------------------------------------------------
   | Test behaviour
   |--------------------------------------------------------------------------
   |
   | This defines the state a feature will assume
   | when you are testing the application this
   | ensures every feature is always tested.
   |
   */

    'test_behaviour' => Feature::ON,
];