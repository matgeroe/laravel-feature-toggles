<?php

namespace MatthiasWilbrink\FeatureToggle\Exceptions;

class FeatureNotFoundException extends \Exception
{

    /**
     * The name of the feature that could not be found.
     * @var string $featureName
     */
    public $featureName;

    /**
     * FeatureNotFoundException constructor.
     * @param string $featureName
     */
    public function __construct(string $featureName)
    {
        $this->featureName = $featureName;

        parent::__construct("", 0, null);
    }
}