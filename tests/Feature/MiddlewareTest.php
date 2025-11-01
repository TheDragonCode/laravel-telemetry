<?php

declare(strict_types=1);

use DragonCode\LaravelRequestTracker\Http\Middleware\RequestTrackerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

it('sets headers and context for guest user', function () {
    $middleware = new RequestTrackerMiddleware;

    $captured = null;

    $response = $middleware->handle(
        makeRequest(ip: '198.51.100.42'),
        function (Request $request) use (&$captured): Response {
            $captured = $request;

            return new Response('OK', 200);
        }
    );

    expect($response->getStatusCode())->toBe(200);

    $userId = $captured->headers->get('X-Tracker-User-Id');
    $ip     = $captured->headers->get('X-Tracker-Ip');
    $trace  = $captured->headers->get('X-Tracker-Trace-Id');

    expect($userId)->toBe('0');
    expect($ip)->toBe('198.51.100.42');
    expect($trace)->not()->toBeEmpty()->and(Str::isUuid($trace))->toBeTrue();

    $context = context('tracker');

    expect($context)
        ->toBeArray()
        ->toHaveKeys(['userId', 'ip', 'traceId']);

    expect($context['userId'])->toBe('0');
    expect($context['ip'])->toBe('198.51.100.42');
    expect($context['traceId'])->toBe($trace);
});

it('uses authenticated user id when available', function () {
    $middleware = new RequestTrackerMiddleware;

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

    $userId = $captured->headers->get('X-Tracker-User-Id');

    expect($userId)->toBe('123');

    $context = context('tracker');

    expect($context['userId'])->toBe('123');
});

it('respects existing tracker headers', function () {
    $middleware = new RequestTrackerMiddleware;

    $existing = [
        'X-Tracker-User-Id'  => '777',
        'X-Tracker-Ip'       => '192.0.2.55',
        'X-Tracker-Trace-Id' => (string) Str::uuid(),
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

    $context = context('tracker');

    expect($context['userId'])->toBe('777');
    expect($context['ip'])->toBe('192.0.2.55');
    expect($context['traceId'])->toBe($existing['X-Tracker-Trace-Id']);
});
