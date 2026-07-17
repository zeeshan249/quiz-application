<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Prevents the browser (and its back/forward bfcache) from serving a cached
 * copy of a page. This forces a fresh server request on back-navigation so
 * the auth/guest middleware can redirect appropriately — e.g. a logged-in
 * admin pressing "Back" won't see the stale login page, and a logged-out
 * user pressing "Back" won't see the stale dashboard.
 */
class NoCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        return $response;
    }
}
