<?php

namespace App\Http\Controllers;

use App\Services\SupabaseClient;
use App\Support\SupabaseSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function index(): View
    {
        $token = SupabaseSession::accessToken();
        $client = SupabaseClient::fromConfig();

        // Fetch posts with profile join. Counts come from denormalized columns.
        $res = $client->rest('GET', 'posts', $token, [
            'select' => 'id,user_id,content,images,like_count,comment_count,created_at,profiles:user_id(id,username,display_name,avatar_url)',
            'order' => 'created_at.desc',
        ]);

        $posts = ($res['ok'] ?? false) ? (is_array($res['data']) ? $res['data'] : []) : [];

        // Manual fetch comment counts for each post to ensure accuracy
        if (!empty($posts)) {
            $postIds = array_column($posts, 'id');
            if (!empty($postIds)) {
                // Fetch comments count by selecting id from comments where post_id in (...)
                // This ensures we display the correct count even if the denormalized column is out of sync.
                $commentsRes = $client->rest('GET', 'comments', $token, [
                    'select' => 'post_id',
                    'post_id' => 'in.(' . implode(',', $postIds) . ')',
                ]);

                if (($commentsRes['ok'] ?? false) && is_array($commentsRes['data'])) {
                    $counts = array_count_values(array_column($commentsRes['data'], 'post_id'));
                    foreach ($posts as &$p) {
                        $p['comment_count'] = $counts[$p['id']] ?? 0;
                    }
                }
            }
        }

        // Determine if we should show onboarding modal (server-driven)
        $showOnboarding = false;
        $onboardingProfile = null;

        if (SupabaseSession::isAuthenticated()) {
            $user = SupabaseSession::user();
            $userId = $user['id'] ?? null;

            $profileRes = $client->rest('GET', 'profiles', $token, [
                'select' => 'id,username,display_name,bio,onboarded',
                'id' => 'eq.' . $userId,
                'limit' => '1',
            ]);

            if (($profileRes['ok'] ?? false) && is_array($profileRes['data']) && count($profileRes['data']) > 0) {
                $onboardingProfile = $profileRes['data'][0];
                $showOnboarding = empty($onboardingProfile['onboarded']);
            }
        }

        return view('home.home', [
            'posts' => $posts,
            'showOnboarding' => $showOnboarding,
            'onboardingProfile' => $onboardingProfile,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!SupabaseSession::isAuthenticated()) {
            return redirect()->route('login')->with('flash_error', 'Vui lòng đăng nhập để đăng bài.');
        }

        $data = $request->validate([
            'content' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:5120'], // Max 5MB
        ]);

        $token = SupabaseSession::accessToken();
        $user = SupabaseSession::user();

        $client = SupabaseClient::fromConfig();
        $imageUrls = null;

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'posts/' . $user['id'] . '/' . time() . '_' . $file->getClientOriginalName();

            // Upload to 'images' bucket (ensure this bucket exists in Supabase and is public)
            $upload = $client->uploadStorage(
                'images',
                $path,
                file_get_contents($file->getRealPath()),
                $file->getMimeType(),
                $token
            );

            if (($upload['ok'] ?? false)) {
                // Store as array since DB column might be JSONB or text[]
                $imageUrls = [$upload['public_url']];
            } else {
                return back()->withInput()->with('flash_error', 'Không thể tải ảnh lên.');
            }
        }

        $res = $client->rest(
            'POST',
            'posts',
            $token,
            ['select' => 'id'],
            [
                'user_id' => $user['id'] ?? null,
                'content' => $data['content'],
                'images' => $imageUrls, // Add images field
            ],
            [
                'Prefer' => 'return=representation',
            ],
        );

        if (($res['ok'] ?? false) !== true) {
            $errMsg = data_get($res, 'data.message')
                ?? data_get($res, 'data.msg')
                ?? data_get($res, 'data.hint')
                ?? data_get($res, 'raw');

            $msg = 'Không thể đăng bài.';
            if (is_string($errMsg) && trim($errMsg) !== '') {
                $msg .= ' ' . trim($errMsg);
            }

            return back()->withInput()->with('flash_error', $msg);
        }

        return redirect()->route('home')->with('flash_success', 'Đăng bài thành công.');
    }
}
