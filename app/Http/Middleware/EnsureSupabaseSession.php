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
        // Check xem có cookie
        view()->share('hasSessionCookie', (bool) $request->cookies->get(config('session.cookie')));

        // Nếu khong có session thì kệ
        if (!SupabaseSession::isAuthenticated()) {
            view()->share('authUser', null);
            return $next($request);
        }

        // Refresh lại token nếu hết hạn
        if (SupabaseSession::isExpired()) {
            $refreshToken = SupabaseSession::refreshToken();

            if ($refreshToken) {
                $client = SupabaseClient::fromConfig();
                $refreshed = $client->refreshToken($refreshToken);

                if (($refreshed['ok'] ?? false) === true) {
                    SupabaseSession::put($refreshed);
                } else {
                    // Token lỗi thì xóa session
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
