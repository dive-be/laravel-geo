# Translate IP addresses into geo locations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dive-be/laravel-geo.svg?style=flat-square)](https://packagist.org/packages/dive-be/laravel-geo)

⚠️ Minor releases of this package may cause breaking changes as it has no stable release yet.

## What problem does this package solve?

Depending on the context of your application, you may want to display content tailored for a specific country/region of the visitor. This package will help with providing a sensible default using IP addresses if it is a first time visit for a user. 

## Installation

You can install the package via composer:

```bash
composer require dive-be/laravel-geo
```

You can publish the config file with:
```bash
php artisan geo:install
```

## Usage

### Services

First, you will have to decide which service to use.

- [GeoIP2 Databases](https://www.maxmind.com/en/geoip2-databases)
- [GeoIP2 Precision Web Services](https://www.maxmind.com/en/geoip2-precision-services)

When you've decided, set the `GEO_DETECTORS_DRIVER` environment variable to the correct value. Refer to the configuration file for the right values.

#### GeoIP2 Databases

- Generate a license key
- Set the `GEO_DETECTORS_MAXMIND_DB_LICENSE_KEY` environment variable to your license key

#### GeoIP2 Precision Web Services

- Get `account_id` & `license_key`
- Set the `GEO_DETECTORS_MAXMIND_WEB_ACCOUNT_ID` & `GEO_DETECTORS_MAXMIND_WEB_LICENSE_KEY` environemnt variables

#### Static

The static driver is meant for usage during local development and testing. 
You should not use it in any other environment as it is always going to return the fallback value.

### Updating local database

IP address ranges tend to become out of date over time. 
Therefore, this package also provides a convenient update command which you can schedule to run once a week to keep everything fresh.

```php
// Console\Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('geo:update')->weekly();
}
```

Only applicable if using MaxMind's *GeoIP2 Databases*.

### Detecting a visitor's geo location

Add the `DetectGeoLocation` (or alias `geo`) middleware to your kernel's `web` middleware stack and you are good to go.
If your app is behind a proxy/load balancer, make sure `DetectGeoLocation` is defined **after** `TrustProxies`.

```php
'web' => [
    // omitted for brevity
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \Dive\Geo\Middleware\DetectGeoLocation::class,
],
```

### Retrieving the detected geo location

There are multiple ways to retrieve the detected ISO alpha-2 country code.

#### Facade

```php
use Dive\Geo\Facades\Geo;

Geo::get();
```

or using the alias

```php
use Geo;

Geo::get();
```

#### Helper

```php
geo()->get()
```

#### Dependency injection

```php
use Dive\Geo\Contracts\Repository;

public function __invoke(Repository $geo)
{
    $geo->get();
}
```

### Transforming the detected geo location

It is a high probability that you'd like to transform the ISO alpha-2 country code into your own Eloquent model or value object
after calling `get` on the repository class. You may define your own `CountryTransformer` which implements the `Transformer` interface
and simply returns the desired object.

e.g.

```php
namespace App\Transformers;

use App\Models\Country;
use Dive\Geo\Contracts\Transformer;

class CountryTransformer implements Transformer
{
    public function transform(string $iso): Country
    {
        return Country::findByIso($iso);
    }
}
```

After defining the class, make sure to provide the FQCN in the configuration file.

```php
'transformer' => App\Transformers\CountryTransformer::class,
```

### Overwriting existing country

At any point in time, you may overwrite the detected country code. Simply call:

```php
geo('TR'); // helper
Geo::put('TR'); // facade
$geo->put('TR'); // injected
```

### Clearing the cache

When enabled, the translated addresses will be held in cache for a certain amount of time defined in the configuration file.
If you'd like to wipe these translations, use the command:

```shell
php artisan geo:clear
```

Please note that the cache driver must support tagging or else everything will be cleared when the command above is run.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email oss@dive.be instead of using the issue tracker.

## Credits

- [Muhammed Sari](https://github.com/mabdullahsari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
