<?php

use MatthiasWilbrink\FeatureToggle\Models\Feature;


return [

    /*
    |--------------------------------------------------------------------------
    | Available features
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'features' => [
        'demo_feature'=> Feature::OFF,
    ],

    'default_behaviour'=> Feature::OFF,
];