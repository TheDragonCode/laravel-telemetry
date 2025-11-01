# 🪢 Laravel Request Tracker

![the dragon code laravel request tracker](https://preview.dragon-code.pro/the%20dragon%20code/request-tracker.svg?brand=laravel&mode=auto)

[![Stable Version][badge_stable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]

Laravel adapter for dragon-code/request-tracker to trace inter-service request chains.

## Installation

You can install the **Laravel Request Tracker** package via [Composer](https://getcomposer.org):

```Bash
composer require dragon-code/laravel-request-tracker
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="tracker"
```

## Basic Usage

Register the middleware:

```php
use DragonCode\LaravelRequestTracker\Http\Middleware\RequestTrackerMiddleware;
use Illuminate\Foundation\Configuration\Middleware;

->withMiddleware(function (Middleware $middleware): void {
     $middleware->prepend(RequestTrackerMiddleware::class);
})
```

That's all 🙂

## How It Works

The middleware monitors request tracker headers in incoming requests and, when present,
automatically injects them into the application context.

This makes it possible to build chains of inter-service requests with filtering by an identifier.

## License

This package is licensed under the [MIT License](LICENSE).


[badge_downloads]:      https://img.shields.io/packagist/dt/dragon-code/laravel-request-tracker.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/dragon-code/laravel-request-tracker.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/TheDragonCode/laravel-request-tracker?label=packagist&style=flat-square

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/dragon-code/laravel-request-tracker
