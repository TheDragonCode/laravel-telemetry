<?php

declare(strict_types=1);

namespace DragonCode\LaravelTracker\Http\Middleware;

use Closure;
use DragonCode\Telemetry\TelemetryHeader;
use DragonCode\Telemetry\TelemetryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

use function config;

class TelemetryMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $telemetry = $this->telemetry($request);

        $this->trace($telemetry);
        $this->context($telemetry);

        return $this->modify($telemetry, $next);
    }

    protected function modify(TelemetryRequest $telemetry, Closure $next): Response
    {
        return $next(
            $telemetry->getRequest()
        );
    }

    protected function trace(TelemetryRequest $telemetry): void
    {
        $telemetry->userId($telemetry->getRequest()?->user()?->getKey());
        $telemetry->ip();
        $telemetry->traceId();
    }

    protected function telemetry(Request $request): TelemetryRequest
    {
        return new TelemetryRequest($request, $this->headers());
    }

    protected function headers(): TelemetryHeader
    {
        return new TelemetryHeader(
            userId : config()?->string('telemetry.headers.user_id'),
            ip     : config()?->string('telemetry.headers.ip'),
            traceId: config()?->string('telemetry.headers.trace_id'),
        );
    }

    protected function context(TelemetryRequest $request): void
    {
        Context::add($this->key(), [
            'userId'  => $request->getUserId(),
            'ip'      => $request->getIp(),
            'traceId' => $request->getTraceId(),
        ]);
    }

    protected function key(): string
    {
        return config('telemetry.context.key');
    }
}
