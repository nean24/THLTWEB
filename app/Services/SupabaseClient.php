<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class SupabaseClient
{
    public function __construct(
        private readonly string $url,
        private readonly string $anonKey,
    ) {}

    public static function fromConfig(): self
    {
        return new self(
            (string) config('services.supabase.url'),
            (string) config('services.supabase.anon_key'),
        );
    }

    private function baseHeaders(?string $accessToken = null): array
    {
        $headers = [
            'apikey' => $this->anonKey,
            'Accept' => 'application/json',
        ];

        if ($accessToken) {
            $headers['Authorization'] = 'Bearer ' . $accessToken;
        }

        return $headers;
    }

    private function http(?string $accessToken = null): PendingRequest
    {
        return Http::baseUrl($this->url)
            ->acceptJson()
            ->withHeaders($this->baseHeaders($accessToken));
    }

    public function signInWithPassword(string $email, string $password): array
    {
        $res = $this->http()
            ->post('/auth/v1/token?grant_type=password', [
                'email' => $email,
                'password' => $password,
            ]);

        if (!$res->successful()) {
            return [
                'ok' => false,
                'error' => $res->json(),
                'status' => $res->status(),
            ];
        }

        $json = $res->json();

        return [
            'ok' => true,
            'session' => $json,
            'access_token' => Arr::get($json, 'access_token'),
            'refresh_token' => Arr::get($json, 'refresh_token'),
            'expires_in' => (int) Arr::get($json, 'expires_in', 0),
            'user' => Arr::get($json, 'user'),
        ];
    }

    public function signUp(string $email, string $password): array
    {
        $res = $this->http()->post('/auth/v1/signup', [
            'email' => $email,
            'password' => $password,
        ]);

        if (!$res->successful()) {
            return [
                'ok' => false,
                'error' => $res->json(),
                'status' => $res->status(),
            ];
        }

        return [
            'ok' => true,
            'data' => $res->json(),
        ];
    }
    //Refresh token lại sau khi đăng nhập hay đăng kí
    public function refreshToken(string $refreshToken): array
    {
        $res = $this->http()
            ->post('/auth/v1/token?grant_type=refresh_token', [
                'refresh_token' => $refreshToken,
            ]);

        if (!$res->successful()) {
            return [
                'ok' => false,
                'error' => $res->json(),
                'status' => $res->status(),
            ];
        }

        $json = $res->json();

        return [
            'ok' => true,
            'session' => $json,
            'access_token' => Arr::get($json, 'access_token'),
            'refresh_token' => Arr::get($json, 'refresh_token'),
            'expires_in' => (int) Arr::get($json, 'expires_in', 0),
            'user' => Arr::get($json, 'user'),
        ];
    }

    //Bỏ token khi đăng xuất
    public function signOut(string $accessToken): array
    {
        $res = $this->http($accessToken)->post('/auth/v1/logout');

        return [
            'ok' => $res->successful(),
            'status' => $res->status(),
            'data' => $res->json(),
        ];
    }

    //Lấy người dùng hiện tại từ token hiện tại
    public function getUser(string $accessToken): array
    {
        $res = $this->http($accessToken)->get('/auth/v1/user');

        if (!$res->successful()) {
            return [
                'ok' => false,
                'status' => $res->status(),
                'error' => $res->json(),
            ];
        }

        return [
            'ok' => true,
            'user' => $res->json(),
        ];
    }

    /**
     * Generic PostgREST call.
     *
     * @param array<string,string> $query
     */
    public function rest(string $method, string $tableOrPath, ?string $accessToken = null, array $query = [], mixed $body = null, array $headers = []): array
    {
        $path = str_starts_with($tableOrPath, '/') ? $tableOrPath : '/rest/v1/' . ltrim($tableOrPath, '/');

        $req = $this->http($accessToken)
            ->withHeaders($headers)
            ->withOptions(['query' => $query]);

        $res = match (strtoupper($method)) {
            'GET' => $req->get($path),
            'POST' => $req->post($path, $body ?? []),
            'PATCH' => $req->patch($path, $body ?? []),
            'DELETE' => $req->delete($path),
            default => $req->send($method, $path, ['json' => $body ?? []]),
        };

        return [
            'ok' => $res->successful(),
            'status' => $res->status(),
            'data' => $res->json(),
            'raw' => $res->body(),
        ];
    }

    public function uploadStorage(string $bucket, string $path, string $content, string $mimeType, ?string $accessToken = null): array
    {
        $urlPath = "/storage/v1/object/$bucket/$path";

        $res = $this->http($accessToken)
            ->withHeaders([
                'Content-Type' => $mimeType,
                'x-upsert' => 'true',
            ])
            ->withBody($content, $mimeType)
            ->post($urlPath);

        if (!$res->successful()) {
            return [
                'ok' => false,
                'status' => $res->status(),
                'error' => $res->json(),
            ];
        }

        $publicUrl = rtrim($this->url, '/') . "/storage/v1/object/public/$bucket/$path";

        return [
            'ok' => true,
            'path' => $path,
            'public_url' => $publicUrl,
        ];
    }
}
