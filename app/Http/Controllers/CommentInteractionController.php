<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentInteractionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
        ]);

        $commentId = $request->comment_id;
        $userId = Auth::id();
        $ipAddress = $request->ip();

        $query = CommentInteraction::where('comment_id', $commentId)
            ->where(function ($q) use ($userId, $ipAddress) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('ip_address', $ipAddress);
                }
            });

        $existingInteraction = $query->first();

        if ($existingInteraction) {
            // User is unliking the comment
            $existingInteraction->delete();
        } else {
            // User is liking the comment
            CommentInteraction::create([
                'comment_id' => $commentId,
                'user_id' => $userId,
                'ip_address' => $userId ? null : $ipAddress,
            ]);
        }

        $likesCount = Comment::findOrFail($commentId)->interactions()->count();

        return response()->json([
            'likes' => $likesCount,
        ]);
    }
}
