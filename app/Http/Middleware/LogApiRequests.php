<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogApiRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Log semua request ke API questions
        if (str_contains($request->path(), 'api/questions')) {
            Log::info('API Request:', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'headers' => $request->headers->all(),
                'content_type' => $request->header('Content-Type'),
                'raw_content' => $request->getContent(),
                'request_all' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);
        }

        return $next($request);
    }
}
