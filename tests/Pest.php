<?php

declare(strict_types=1);

use Orchestra\Testbench\TestCase;

pest()
    ->printer()
    ->compact();

pest()
    ->extend(TestCase::class)
    ->in('Feature');
