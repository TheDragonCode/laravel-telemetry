<?php

declare(strict_types=1);

namespace Tests;

use DragonCode\LaravelRequestTracker\LaravelRequestTrackerServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelRequestTrackerServiceProvider::class,
        ];
    }
}
