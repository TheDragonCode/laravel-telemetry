<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Laravel Telemetry — configuration
|--------------------------------------------------------------------------
|
| Here you can configure the behavior of the telemetry collection package.
| The file reads your code at runtime and is not cached separately.
| If necessary, publish the configuration file and adjust the values to
| your needs.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Telemetry context
    |--------------------------------------------------------------------------
    |
    | The root context key. Used to group telemetry data in arrays/logs/reporters.
    | Change only when explicitly necessary.
    |
    */
    'context' => [
        'key' => 'telemetry',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP header names
    |--------------------------------------------------------------------------
    |
    | Configure the names of incoming/outgoing headers that will be used to
    | transfer telemetry data between services.
    |
    | They must remain consistent across all services that exchange telemetry.
    |
    */
    'headers' => [

        // Authenticated user's identifier (if any)
        'user_id' => 'X-Telemetry-User-Id',

        // Client IP address captured on ingress
        'ip' => 'X-Telemetry-Ip',

        // Request trace identifier (trace/span)
        'trace_id' => 'X-Telemetry-Trace-Id',
    ],
];
