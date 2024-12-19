<?php

namespace AbdulQuadri\ActivityLogger\Middleware;

use Closure;
use Illuminate\Http\Request;
use AbdulQuadri\ActivityLogger\Models\Activity;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (config('activity-logger.track_page_visits', true) && auth()->check()) {
            Activity::create([
                'user_id' => auth()->id(),
                'type' => 'page_visited',
                'payload' => [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'route' => $request->route() ? $request->route()->getName() : null,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return $next($request);
    }
}

