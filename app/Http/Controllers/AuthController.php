<?php

namespace App\Http\Controllers;

use App\Services\SupabaseClient;
use App\Support\SupabaseSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $client = SupabaseClient::fromConfig();
        $res = $client->signInWithPassword($data['email'], $data['password']);

        if (($res['ok'] ?? false) !== true || empty($res['access_token'])) {
            $msg = 'Không đăng nhập được.';
            $errMsg = data_get($res, 'error.error_description')
                ?? data_get($res, 'error.msg')
                ?? data_get($res, 'error.message');

            if (is_string($errMsg) && $errMsg !== '') {
                // Common Supabase case: email confirmation required
                if (str_contains(strtolower($errMsg), 'email') && str_contains(strtolower($errMsg), 'confirm')) {
                    $msg = 'Email chưa được xác minh. Vui lòng kiểm tra email để xác minh hoặc tắt Email Confirm trong Supabase Auth.';
                } else {
                    $msg = $errMsg;
                }
            } else {
                $msg = 'Sai email hoặc mật khẩu.';
            }

            return back()
                ->withInput($request->only('email'))
                ->with('flash_error', $msg);
        }

        // Ensure a new session is issued so the browser receives the session cookie.
        $request->session()->regenerate();

        SupabaseSession::put($res);

        // Make sure the handler persists the session before redirect.
        $request->session()->save();

        return redirect()->route('home')->with('flash_success', 'Đăng nhập thành công.');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
            'confirm_password' => ['required', 'same:password'],
        ]);

        $client = SupabaseClient::fromConfig();
        $res = $client->signUp($data['email'], $data['password']);

        if (($res['ok'] ?? false) !== true) {
            return back()->withInput($request->only('email'))->with('flash_error', 'Đăng ký thất bại.');
        }

        // Supabase may require email confirmation.
        return redirect()->route('login')->with('flash_success', 'Đăng ký thành công. Vui lòng đăng nhập.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $token = SupabaseSession::accessToken();
        $client = SupabaseClient::fromConfig();

        if ($token) {
            $client->signOut($token);
        }

        SupabaseSession::forget();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('flash_success', 'Đã đăng xuất.');
    }
}
