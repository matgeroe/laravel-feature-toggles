<?php

namespace MatthiasWilbrink\FeatureToggle\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Feature
 *
 * @property int $id
 * @property string $name
 * @property bool $state
 * @package MatthiasWilbrink\FeatureToggle\Models
 */
class Feature extends Model
{
    /**
     * The ON state
     */
    public const ON = 1;

    /**
     * The OFF state
     */
    public const OFF = 0;

    /**
     * @var string
     */
    protected $table = 'feature_toggles';
}