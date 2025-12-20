<?php

namespace App\Http\Controllers;

use App\Services\SupabaseClient;
use App\Support\SupabaseSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        if (!SupabaseSession::isAuthenticated()) {
            redirect()->route('login')->send();
            exit;
        }

        $token = SupabaseSession::accessToken();
        $user = SupabaseSession::user();
        $userId = $user['id'] ?? null;

        $client = SupabaseClient::fromConfig();

        // Profile
        $profileRes = $client->rest('GET', 'profiles', $token, [
            'select' => 'id,username,display_name,bio,avatar_url,created_at,onboarded',
            'id' => 'eq.' . $userId,
            'limit' => '1',
        ]);

        $profile = null;
        if (($profileRes['ok'] ?? false) && is_array($profileRes['data']) && count($profileRes['data']) > 0) {
            $profile = $profileRes['data'][0];
        }

        // My posts
        $myPostsRes = $client->rest('GET', 'posts', $token, [
            'select' => 'id,user_id,content,images,like_count,comment_count,created_at,profiles:user_id(id,username,display_name,avatar_url)',
            'user_id' => 'eq.' . $userId,
            'order' => 'created_at.desc',
        ]);
        $myPosts = (($myPostsRes['ok'] ?? false) && is_array($myPostsRes['data'])) ? $myPostsRes['data'] : [];

        // Liked posts: get likes => join posts
        $likedRes = $client->rest('GET', 'likes', $token, [
            'select' => 'post_id,created_at,posts:post_id(id,user_id,content,images,like_count,comment_count,created_at,profiles:user_id(id,username,display_name,avatar_url))',
            'user_id' => 'eq.' . $userId,
            'order' => 'created_at.desc',
        ]);

        $likedPosts = [];
        if (($likedRes['ok'] ?? false) && is_array($likedRes['data'])) {
            foreach ($likedRes['data'] as $row) {
                if (!empty($row['posts'])) {
                    $likedPosts[] = $row['posts'];
                }
            }
        }

        return view('profile.index', [
            'profile' => $profile,
            'myPosts' => $myPosts,
            'likedPosts' => $likedPosts,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        if (!SupabaseSession::isAuthenticated()) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'display_name' => ['nullable', 'string', 'max:80'],
            'username' => ['required', 'string', 'max:24'],
            'bio' => ['nullable', 'string', 'max:200'],
            'avatar_url' => ['nullable', 'url', 'max:255'],
            'avatar_file' => ['nullable', 'image', 'max:5120'], // Max 5MB
        ]);

        // Basic username validation (same spirit as old JS)
        if (!preg_match('/^[a-zA-Z0-9_]{3,24}$/', $data['username'])) {
            return back()->withInput()->with('flash_error', 'Username 3–24 ký tự, chỉ chữ/số/_');
        }

        $token = SupabaseSession::accessToken();
        $user = SupabaseSession::user();
        $userId = $user['id'] ?? null;

        $client = SupabaseClient::fromConfig();

        // Handle Avatar Upload
        $avatarUrl = $data['avatar_url'] ?? null;
        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $filename = 'avatar_' . time() . '_' . $file->getClientOriginalName();
            $path = $userId . '/' . $filename;

            // Read file content
            $content = file_get_contents($file->getRealPath());
            $mimeType = $file->getMimeType();

            // Upload to Supabase Storage (bucket: 'avatars')
            // Ensure you have a public bucket named 'avatars'
            $uploadRes = $client->uploadStorage('avatars', $path, $content, $mimeType, $token);

            if ($uploadRes['ok']) {
                $avatarUrl = $uploadRes['public_url'];
            } else {
                // Log error or handle it? For now, just flash error
                // \Log::error('Avatar upload failed', $uploadRes);
                return back()->withInput()->with('flash_error', 'Không thể tải ảnh đại diện lên.');
            }
        }

        $updateRes = $client->rest('PATCH', 'profiles', $token, [
            'id' => 'eq.' . $userId,
            'select' => 'id',
        ], [
            'display_name' => $data['display_name'] ?: null,
            'username' => strtolower($data['username']),
            'bio' => $data['bio'] ?: null,
            'avatar_url' => $avatarUrl,
        ], [
            'Prefer' => 'return=representation',
        ]);

        if (($updateRes['ok'] ?? false) !== true) {
            return back()->withInput()->with('flash_error', 'Không thể cập nhật hồ sơ.');
        }

        return redirect()->route('profile')->with('flash_success', 'Cập nhật hồ sơ thành công.');
    }
}
