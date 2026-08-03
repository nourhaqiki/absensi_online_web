<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NgrokSkipBrowserWarning
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If client sets this header, forward it in response to suppress ngrok HTML warning
        $response = $next($request);

        if ($request->header('ngrok-skip-browser-warning')) {
            $response->headers->set('ngrok-skip-browser-warning', 'true');
        }

        return $response;
    }
}
