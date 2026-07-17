<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts a route to authenticated admin users (user_type === 1).
 *
 * - Guests are sent to the login page.
 * - Authenticated non-admins are logged out and sent to login. (Logging out
 *   first prevents an infinite login <-> dashboard redirect loop, since the
 *   login route's `guest` middleware would otherwise bounce them back.)
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        if ((int) Auth::user()->user_type !== 1) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->withErrors(['credentials' => 'You do not have admin access.']);
        }

        return $next($request);
    }
}
