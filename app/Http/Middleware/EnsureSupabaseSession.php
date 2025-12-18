<?php

namespace App\Http\Middleware;

use App\Services\SupabaseClient;
use App\Support\SupabaseSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSupabaseSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // Quick signal for views: do we at least have a session cookie?
        // (Useful for debugging when session payload isn't being persisted.)
        view()->share('hasSessionCookie', (bool) $request->cookies->get(config('session.cookie')));

        // No session -> continue.
        if (!SupabaseSession::isAuthenticated()) {
            view()->share('authUser', null);
            return $next($request);
        }

        // Refresh token if needed.
        if (SupabaseSession::isExpired()) {
            $refreshToken = SupabaseSession::refreshToken();

            if ($refreshToken) {
                $client = SupabaseClient::fromConfig();
                $refreshed = $client->refreshToken($refreshToken);

                if (($refreshed['ok'] ?? false) === true) {
                    SupabaseSession::put($refreshed);
                } else {
                    // Token invalid -> clear session.
                    SupabaseSession::forget();
                }
            } else {
                SupabaseSession::forget();
            }
        }

        view()->share('authUser', SupabaseSession::user());

        return $next($request);
    }
}
