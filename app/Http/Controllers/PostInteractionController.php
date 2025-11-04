<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostInteractionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'type' => 'required|in:like,dislike',
        ]);

        $postId = $request->post_id;
        $type = $request->type;
        $userId = Auth::id();
        $ipAddress = $request->ip();

        $query = PostInteraction::where('post_id', $postId)
            ->where(function ($q) use ($userId, $ipAddress) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('ip_address', $ipAddress);
                }
            });

        $existingInteraction = $query->first();

        if ($existingInteraction) {
            if ($existingInteraction->type === $type) {
                // User clicked the same button again (unlike or undislike)
                $existingInteraction->delete();
            } else {
                // User changed their interaction (e.g., from like to dislike)
                $existingInteraction->update(['type' => $type]);
            }
        } else {
            // New interaction
            PostInteraction::create([
                'post_id' => $postId,
                'user_id' => $userId,
                'ip_address' => $userId ? null : $ipAddress,
                'type' => $type,
            ]);
        }

        $post = Post::findOrFail($postId);
        $likesCount = $post->likes()->count();
        $dislikesCount = $post->dislikes()->count();

        return response()->json([
            'likes' => $likesCount,
            'dislikes' => $dislikesCount,
        ]);
    }
}
