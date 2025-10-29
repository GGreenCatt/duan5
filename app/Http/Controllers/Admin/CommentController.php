<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('post', 'user')->latest()->paginate(10);
        return view('admin.comments.index', compact('comments'));
    }

    public function approve(Comment $comment)
    {
        $comment->status = 'approved';
        $comment->save();
        return response()->json(['success' => true, 'message' => 'Bình luận đã được phê duyệt.']);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['success' => true, 'message' => 'Bình luận đã được xóa.']);
    }

    public function reject(Comment $comment)
    {
        $comment->status = 'rejected';
        $comment->save();
        return response()->json(['success' => true, 'message' => 'Bình luận đã bị từ chối.']);
    }
}
