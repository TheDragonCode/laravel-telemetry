<?php

declare(strict_types=1);

return [
    'context' => [
        'key' => 'telemetry',
    ],

    'headers' => [
        'user_id'  => 'X-Telemetry-User-Id',
        'ip'       => 'X-Telemetry-Ip',
        'trace_id' => 'X-Telemetry-Trace-Id',
    ],
];
