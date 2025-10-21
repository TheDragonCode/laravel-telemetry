<?php

declare(strict_types=1);

namespace DragonCode\LaravelTracker;

use Illuminate\Support\ServiceProvider;

class LaravelTelemetryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/telemetry.php' => $this->app->configPath('telemetry.php'),
        ], ['config', 'telemetry']);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/telemetry.php', 'telemetry');
    }
}
