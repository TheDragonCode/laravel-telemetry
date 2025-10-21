<?php

declare(strict_types=1);

use DragonCode\LaravelTracker\Http\Middleware\TelemetryMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

it('sets headers and context for guest user', function () {
    $middleware = new TelemetryMiddleware;

    $captured = null;

    $response = $middleware->handle(
        makeRequest(ip: '198.51.100.42'),
        function (Request $request) use (&$captured): Response {
            $captured = $request;

            return new Response('OK', 200);
        }
    );

    expect($response->getStatusCode())->toBe(200);

    $userId = $captured->headers->get('X-Telemetry-User-Id');
    $ip     = $captured->headers->get('X-Telemetry-Ip');
    $trace  = $captured->headers->get('X-Telemetry-Trace-Id');

    expect($userId)->toBe('0');
    expect($ip)->toBe('198.51.100.42');
    expect($trace)->not()->toBeEmpty()->and(Str::isUuid($trace))->toBeTrue();

    // context('telemetry') is added by middleware
    $context = context('telemetry');

    expect($context)
        ->toBeArray()
        ->toHaveKeys(['userId', 'ip', 'traceId']);

    expect($context['userId'])->toBe('0');
    expect($context['ip'])->toBe('198.51.100.42');
    expect($context['traceId'])->toBe($trace);
});

it('uses authenticated user id when available', function () {
    $middleware = new TelemetryMiddleware;

    $user = new class {
        public function getKey(): int
        {
            return 123;
        }
    };

    $captured = null;

    $middleware->handle(
        makeRequest(userResolver: fn () => $user),
        function (Request $request) use (&$captured): Response {
            $captured = $request;

            return new Response('OK', 200);
        }
    );

    $userId = $captured->headers->get('X-Telemetry-User-Id');

    expect($userId)->toBe('123');

    $context = context('telemetry');

    expect($context['userId'])->toBe('123');
});

it('respects existing telemetry headers', function () {
    $middleware = new TelemetryMiddleware;

    $existing = [
        'X-Telemetry-User-Id'  => '777',
        'X-Telemetry-Ip'       => '192.0.2.55',
        'X-Telemetry-Trace-Id' => (string) Str::uuid(),
    ];

    $captured = null;

    $middleware->handle(
        makeRequest(headers: $existing),
        function (Request $request) use (&$captured): Response {
            $captured = $request;

            return new Response('OK', 200);
        }
    );

    foreach ($existing as $key => $value) {
        expect($captured->headers->get($key))->toBe($value);
    }

    $context = context('telemetry');

    expect($context['userId'])->toBe('777');
    expect($context['ip'])->toBe('192.0.2.55');
    expect($context['traceId'])->toBe($existing['X-Telemetry-Trace-Id']);
});
