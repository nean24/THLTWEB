<?php

namespace App\Support;

use Carbon\CarbonImmutable;

class SupabaseSession
{
    public const SESSION_KEY = 'supabase';

    public static function put(array $session): void
    {
        // Normalise and persist what we need.
        $expiresIn = (int) ($session['expires_in'] ?? 0);

        // Supabase returns a large user payload; storing it in a cookie-based session
        // easily exceeds the 4096-byte Set-Cookie limit.
        $user = $session['user'] ?? null;
        $userSlim = null;
        if (is_array($user)) {
            $userSlim = [
                'id' => $user['id'] ?? null,
                'email' => $user['email'] ?? null,
            ];
        }

        session([
            self::SESSION_KEY => [
                'access_token' => $session['access_token'] ?? null,
                'refresh_token' => $session['refresh_token'] ?? null,
                'expires_at' => CarbonImmutable::now()->addSeconds(max(0, $expiresIn))->timestamp,
                'user' => $userSlim,
            ],
        ]);
    }

    public static function forget(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public static function data(): array
    {
        return (array) session(self::SESSION_KEY, []);
    }

    public static function accessToken(): ?string
    {
        return self::data()['access_token'] ?? null;
    }

    public static function refreshToken(): ?string
    {
        return self::data()['refresh_token'] ?? null;
    }

    public static function expiresAt(): ?int
    {
        $v = self::data()['expires_at'] ?? null;
        return $v !== null ? (int) $v : null;
    }

    public static function user(): ?array
    {
        $u = self::data()['user'] ?? null;
        return is_array($u) ? $u : null;
    }

    public static function isAuthenticated(): bool
    {
        return (bool) self::accessToken();
    }

    public static function isExpired(int $skewSeconds = 30): bool
    {
        $exp = self::expiresAt();
        if (!$exp) {
            return true;
        }

        return $exp <= CarbonImmutable::now()->addSeconds($skewSeconds)->timestamp;
    }
}
