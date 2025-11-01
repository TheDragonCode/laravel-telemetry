<?php

declare(strict_types=1);

namespace DragonCode\LaravelRequestTracker\Http\Middleware;

use Closure;
use DragonCode\RequestTracker\TrackerHeader;
use DragonCode\RequestTracker\TrackerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

use function config;

class RequestTrackerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $tracker = $this->tracker($request);

        $this->trace($tracker);
        $this->context($tracker);

        return $this->modify($tracker, $next);
    }

    protected function modify(TrackerRequest $tracker, Closure $next): Response
    {
        return $next(
            $tracker->getRequest()
        );
    }

    protected function trace(TrackerRequest $tracker): void
    {
        $tracker->userId($tracker->getRequest()?->user()?->getKey());
        $tracker->ip();
        $tracker->traceId();
    }

    protected function tracker(Request $request): TrackerRequest
    {
        return new TrackerRequest($request, $this->headers());
    }

    protected function headers(): TrackerHeader
    {
        return new TrackerHeader(
            userId : config()?->string('request-tracker.headers.user_id'),
            ip     : config()?->string('request-tracker.headers.ip'),
            traceId: config()?->string('request-tracker.headers.trace_id'),
        );
    }

    protected function context(TrackerRequest $request): void
    {
        Context::add($this->key(), [
            'userId'  => $request->getUserId(),
            'ip'      => $request->getIp(),
            'traceId' => $request->getTraceId(),
        ]);
    }

    protected function key(): string
    {
        return config('request-tracker.context.key');
    }
}
