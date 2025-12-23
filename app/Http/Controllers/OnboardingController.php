<?php

namespace App\Http\Controllers;

use App\Services\SupabaseClient;
use App\Support\SupabaseSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (!SupabaseSession::isAuthenticated()) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'username' => ['required', 'string', 'max:24'],
            'display_name' => ['nullable', 'string', 'max:80'],
            'bio' => ['nullable', 'string', 'max:200'],
        ]);

        if (!preg_match('/^[a-zA-Z0-9_]{3,24}$/', $data['username'])) {
            return back()->withInput()->with('flash_error', 'Username 3–24 ký tự, chỉ chữ/số/_');
        }

        $token = SupabaseSession::accessToken();
        $user = SupabaseSession::user();
        $userId = $user['id'] ?? null;

        $client = SupabaseClient::fromConfig();

        // Check xem tên username đã tồn tại chưa
        $exists = $client->rest('GET', 'profiles', $token, [
            'select' => 'id',
            'username' => 'ilike.' . $data['username'],
            'id' => 'neq.' . $userId,
            'limit' => '1',
        ]);

        if (($exists['ok'] ?? false) && is_array($exists['data']) && count($exists['data']) > 0) {
            return back()->withInput()->with('flash_error', 'Username đã được sử dụng.');
        }

        $update = $client->rest('PATCH', 'profiles', $token, [
            'id' => 'eq.' . $userId,
            'select' => 'id',
        ], [
            'username' => strtolower($data['username']),
            'display_name' => ($data['display_name'] ?? '') !== '' ? $data['display_name'] : strtolower($data['username']),
            'bio' => ($data['bio'] ?? '') !== '' ? $data['bio'] : null,
            'onboarded' => true,
        ], [
            'Prefer' => 'return=representation',
        ]);

        if (($update['ok'] ?? false) !== true) {
            return back()->withInput()->with('flash_error', 'Không thể lưu onboarding.');
        }

        return redirect()->route('home')->with('flash_success', 'Cập nhật thông tin thành công.');
    }
}
