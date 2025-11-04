<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = new Comment();
        $comment->post_id = $validatedData['post_id'];
        $comment->content = $validatedData['content'];
        $comment->parent_id = $validatedData['parent_id'] ?? null;

        if (Auth::check()) {
            $comment->user_id = Auth::id();
            // Admins' comments are automatically approved
            if (Auth::user()->role === 'Admin') {
                $comment->status = 'approved';
            } else {
                $comment->status = 'pending';
            }
        } else {
            // Guests' comments are always pending
            $comment->status = 'pending';
            // Check if anonymous name exists in session
            if (session()->has('anonymous_name')) {
                $comment->anonymous_name = session('anonymous_name');
            } else {
                // Generate and store a new anonymous name in session
                $newAnonymousName = 'Người dùng ẩn danh ' . rand(1000, 9999);
                session(['anonymous_name' => $newAnonymousName]);
                $comment->anonymous_name = $newAnonymousName;
            }
        }

        $comment->save();

        // Load the user relationship if available
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Bình luận của bạn đã được gửi!',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at_for_humans' => $comment->created_at->diffForHumans(),
                'author' => $comment->user ? $comment->user->name : $comment->anonymous_name,
                'parent_id' => $comment->parent_id,
                'author_role' => $comment->user ? $comment->user->role : null,
                'user_id' => $comment->user_id, // Add user_id
                'anonymous_name' => $comment->anonymous_name, // Ensure anonymous_name is always present
                'status' => $comment->status, // Add status
            ]
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['success' => 'Bình luận đã được xóa thành công.']);
    }
}
