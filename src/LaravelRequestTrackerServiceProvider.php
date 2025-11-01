<?php

declare(strict_types=1);

namespace DragonCode\LaravelRequestTracker;

use Illuminate\Support\ServiceProvider;

class LaravelRequestTrackerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/request-tracker.php' => $this->app->configPath('request-tracker.php'),
        ], ['config', 'tracker']);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/request-tracker.php', 'request-tracker');
    }
}
