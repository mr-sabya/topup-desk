<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAppStatus
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Always allow the status check route itself
        if ($request->is('app-status-check')) {
            return $next($request);
        }

        $isPaused = \App\Models\Setting::where('key', 'is_paused')->first()?->value === '1';

        // 2. If not paused, continue
        if (!$isPaused) {
            return $next($request);
        }

        // 3. If paused, allow Admin and Login
        if ($request->is('admin*') || $request->is('login') || \Illuminate\Support\Facades\Auth::check()) {
            return $next($request);
        }

        // 4. Otherwise show maintenance
        return response()->view('errors.maintenance', [], 503);
    }
}
