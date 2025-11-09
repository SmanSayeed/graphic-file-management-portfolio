<?php

namespace App\Http\Middleware;

use App\Models\AnalyticsEvent;
use App\Models\AnalyticsMetric;
use App\Models\StorageSetting;
use Closure;
use Illuminate\Http\Request;

class TrackAnalytics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            $metrics = AnalyticsMetric::today();
            $metrics->increment('visitors_total');

            AnalyticsEvent::create([
                'event_type' => 'visit',
                'context' => $request->path(),
                'project_id' => null,
                'bytes' => 0,
                'meta' => [
                    'ip' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 255),
                ],
                'occurred_at' => now(),
            ]);
        }

        return $response;
    }

    protected function shouldTrack(Request $request, $response): bool
    {
        if (app()->runningInConsole()) {
            return false;
        }

        if (!StorageSetting::getSettings()->analytics_enabled) {
            return false;
        }

        if (!$request->isMethod('GET')) {
            return false;
        }

        if ($request->is('admin/*') || $request->is('nova-api/*') || $request->is('horizon/*')) {
            return false;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return false;
        }

        if ($response && method_exists($response, 'isRedirection') && $response->isRedirection()) {
            return false;
        }

        return true;
    }
}

