<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('manage-comments');
            return $next($request);
        });
    }

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

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:approve,reject,delete',
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id',
        ]);

        $commentIds = $request->input('comment_ids');
        $action = $request->input('action');
        $count = count($commentIds);

        switch ($action) {
            case 'approve':
                Comment::whereIn('id', $commentIds)->update(['status' => 'approved']);
                return response()->json(['success' => true, 'message' => "Đã phê duyệt thành công {$count} bình luận."]);
            case 'reject':
                Comment::whereIn('id', $commentIds)->update(['status' => 'rejected']);
                return response()->json(['success' => true, 'message' => "Đã từ chối thành công {$count} bình luận."]);
            case 'delete':
                Comment::whereIn('id', $commentIds)->delete();
                return response()->json(['success' => true, 'message' => "Đã xóa thành công {$count} bình luận."]);
        }

        return response()->json(['success' => false, 'message' => 'Hành động không hợp lệ.'], 400);
    }
}
