<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        
        // Log incoming request
        Log::info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'request_data' => $request->except(['pdf_file']), // Exclude file from logging
        ]);

        $response = $next($request);

        // Log response
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // in milliseconds

        Log::info('API Response', [
            'status' => $response->status(),
            'duration_ms' => $duration,
            'memory_usage' => memory_get_usage(true),
        ]);

        return $response;
    }
}
