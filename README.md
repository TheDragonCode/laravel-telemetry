# 🪢 Laravel Telemetry

![the dragon code laravel telemetry](https://preview.dragon-code.pro/the%20dragon%20code/telemetry.svg?brand=laravel&mode=auto)

[![Stable Version][badge_stable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]

End-to-end telemetry of inter-service communication.

## Installation

You can install the **Laravel Telemetry** package via [Composer](https://getcomposer.org):

```Bash
composer require dragon-code/laravel-telemetry
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="telemetry"
```

## Basic Usage

Register the middleware:

```php
use DragonCode\LaravelTracker\Http\Middleware\TelemetryMiddleware;
use Illuminate\Foundation\Configuration\Middleware;

->withMiddleware(function (Middleware $middleware): void {
     $middleware->prepend(TelemetryMiddleware::class);
})
```

That's all 🙂

## How It Works

The middleware monitors telemetry headers in incoming requests and, when present,
automatically injects them into the application context.

This makes it possible to build chains of inter-service requests with filtering by an identifier.

## License

This package is licensed under the [MIT License](LICENSE).


[badge_downloads]:      https://img.shields.io/packagist/dt/dragon-code/laravel-telemetry.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/dragon-code/laravel-telemetry.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/TheDragonCode/laravel-telemetry?label=packagist&style=flat-square

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/dragon-code/laravel-telemetry
