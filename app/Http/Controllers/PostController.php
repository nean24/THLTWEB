<?php

namespace App\Http\Controllers;

use App\Services\SupabaseClient;
use App\Support\SupabaseSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function show(string $id): View
    {
        $token = SupabaseSession::accessToken();
        $client = SupabaseClient::fromConfig();

        $postRes = $client->rest('GET', 'posts', $token, [
            'select' => 'id,user_id,content,images,like_count,comment_count,created_at,profiles:user_id(id,username,display_name,avatar_url)',
            'id' => 'eq.' . $id,
            'limit' => '1',
        ]);

        $post = null;
        if (($postRes['ok'] ?? false) && is_array($postRes['data']) && count($postRes['data']) > 0) {
            $post = $postRes['data'][0];
        }

        $commentsRes = $client->rest('GET', 'comments', $token, [
            'select' => 'id,post_id,user_id,content,created_at',
            'post_id' => 'eq.' . $id,
            'order' => 'created_at.asc',
        ]);

        $comments = (($commentsRes['ok'] ?? false) && is_array($commentsRes['data'])) ? $commentsRes['data'] : [];

        // Manual join profiles to avoid PostgREST join issues
        if (!empty($comments)) {
            $userIds = array_unique(array_column($comments, 'user_id'));
            if (!empty($userIds)) {
                $profilesRes = $client->rest('GET', 'profiles', $token, [
                    'select' => 'id,username,display_name,avatar_url',
                    'id' => 'in.(' . implode(',', $userIds) . ')',
                ]);

                $profilesMap = [];
                if (($profilesRes['ok'] ?? false) && is_array($profilesRes['data'])) {
                    foreach ($profilesRes['data'] as $p) {
                        $profilesMap[$p['id']] = $p;
                    }
                }

                foreach ($comments as &$c) {
                    $c['profiles'] = $profilesMap[$c['user_id']] ?? null;
                }
            }
        }

        // Override comment_count with actual fetched count to ensure consistency
        if ($post) {
            $post['comment_count'] = count($comments);
        }

        return view('post.show', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    public function comment(Request $request, string $id): RedirectResponse
    {
        if (!SupabaseSession::isAuthenticated()) {
            return redirect()->route('login')->with('flash_error', 'Vui lòng đăng nhập để bình luận.');
        }

        $data = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $token = SupabaseSession::accessToken();
        $user = SupabaseSession::user();
        $client = SupabaseClient::fromConfig();

        // Insert comment. Denormalized counter (posts.comment_count) should be handled by DB trigger.
        $insert = $client->rest('POST', 'comments', $token, ['select' => 'id'], [
            'post_id' => $id,
            'user_id' => $user['id'] ?? null,
            'content' => $data['content'],
        ], [
            'Prefer' => 'return=representation',
        ]);

        if (($insert['ok'] ?? false) !== true) {
            return back()->withInput()->with('flash_error', 'Không thể gửi bình luận.');
        }

        return redirect()->route('post.show', ['id' => $id])->with('flash_success', 'Đã gửi bình luận.');
    }

    public function like(Request $request, string $id): RedirectResponse
    {
        if (!SupabaseSession::isAuthenticated()) {
            return redirect()->route('login')->with('flash_error', 'Vui lòng đăng nhập để thả tim.');
        }

        $token = SupabaseSession::accessToken();
        $user = SupabaseSession::user();
        $userId = $user['id'] ?? null;
        $client = SupabaseClient::fromConfig();

        // Check existing like
        $existing = $client->rest('GET', 'likes', $token, [
            'select' => 'user_id,post_id',
            'user_id' => 'eq.' . $userId,
            'post_id' => 'eq.' . $id,
            'limit' => '1',
        ]);

        $hasLike = (($existing['ok'] ?? false) && is_array($existing['data']) && count($existing['data']) > 0);

        if ($hasLike) {
            // Unlike
            $del = $client->rest('DELETE', 'likes', $token, [
                'user_id' => 'eq.' . $userId,
                'post_id' => 'eq.' . $id,
            ]);

            if (($del['ok'] ?? false) !== true) {
                return back()->with('flash_error', 'Không thể bỏ thích.');
            }

            // Counter: should be handled by trigger.
            return back()->with('flash_success', 'Đã bỏ thích.');
        }

        // Like
        $ins = $client->rest('POST', 'likes', $token, ['select' => 'user_id'], [
            'user_id' => $userId,
            'post_id' => $id,
        ], [
            'Prefer' => 'return=representation',
        ]);

        if (($ins['ok'] ?? false) !== true) {
            return back()->with('flash_error', 'Không thể thả tim.');
        }

        // Counter: should be handled by trigger.
        return back()->with('flash_success', 'Đã thả tim.');
    }
}
