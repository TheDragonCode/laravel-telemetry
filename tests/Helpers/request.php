<?php

declare(strict_types=1);

use Illuminate\Http\Request;

function makeRequest(string $ip = '203.0.113.10', ?callable $userResolver = null, array $headers = []): Request
{
    $request = Request::create(uri: '/', server: [
        'REMOTE_ADDR' => $ip,
    ]);

    foreach ($headers as $key => $value) {
        $request->headers->set($key, $value);
    }

    if ($userResolver) {
        $request->setUserResolver($userResolver);
    }

    return $request;
}
