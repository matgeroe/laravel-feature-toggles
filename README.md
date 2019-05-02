Laravel feature toggles
=======================
Toggle parts of you application on or off with zero downtime.  

Deploy your feature at night, toggle it when your boss hits that big red button.  
(big red button not included)

Features
------------
* Set the initial state of a feature in the config
* Use laravel's cache to limit database load
* Convenient commands to toggle features on/off  


Installation
------------
This package is available on composer.  
To install laravel-feature-toggles you can run the following command
```shell
composer require matthiaswilbrink/laravel-feature-toggles
```

After installation you can publish the configuration with this command
```shell
php artisan vendor:publish --provider="MatthiasWilbrink\FeatureToggle\Providers\FeatureToggleServiceProvider"
```
As this package uses a database to keep track of the features' state, you'll have to migrate after installing this package
```shell
php artisan migrate
```

Usage
-----

| Command               | Purpose                                                                                                          |
| --------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `feature:create`      | Read features from the config and inserts them into the database. Once inserted, it will *never* be overwritten. |
| `feature:list`        | List all features in the database.                                                                               |
| `feature:enable`      | Enable a feature, pick from list.                                                                                |
| `feature:disable`     | Disable a feature, pick from list.                                                                               |
| `feature:clear-cache` | Clear the feature cache, features will be stored in cache again when they are called for the first time.         |

### Reading

#### Blade
A custom blade directive has been made.  
In a blade file do the following:
```php
[...]
@feature('name_of_my_feature_as_string')
    This will show when the feature is enabled
@endfeature
[...]
```
The else clause could also be used.
```php
[...]
@feature('name_of_my_feature_as_string')
    This will show when the feature is enabled.
@else
    This will show when the feature is disabled.
@endfeature
[...]
```

#### Helper

The helper function `feature(string $name)` return the state of the given feature.
```php
if (feature('name_of_my_feature_as_string')){
    //do something
}
```

#### Facade

An alias has been registered.
```php
// Check if a feature is enabled
if (Feature::isEnabled('name_of_my_feature_as_string')){
    //do something
}

// A shorter alias
if (Feature::isOn('name_of_my_feature_as_string')){
    //do something
}
```

#### Dependency Injection

Of course you can inject the FeatureManager to accomplish the same goal.
```php
public function someMethod(FeatureManager $featureManager)
{
    if ($featureManager->isEnabled('name_of_my_feature_as_string')){
        //do something
    }
}
```


### Other activities

You might also want to allow your users to enable or disable features. (Things like an Christmas theme come to mind)  
```php
public function turnTheSnowFlakesOn(FeatureManager $featureManager)
{
    $featureManager->enable('homepage_snowflakes_animation');
}
```

A note on caching
-----------------
If caching is turned on, the cache is cleared and rebuild *every* time a feature is enabled/disabled.  
Might the need arise you can always flush the cache with `php artisan feature:clear-cache`.  
This will *only* flush the feature cache, not your regular application cache.  

 