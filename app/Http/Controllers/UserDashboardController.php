<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // Import Post model
use Carbon\Carbon; // Vẫn có thể cần cho định dạng ngày tháng trong view

class UserDashboardController extends Controller
{
    /**
     * Display the user's dashboard with general posts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Lựa chọn 1: Lấy TẤT CẢ bài viết trong hệ thống, mới nhất lên đầu, và phân trang.
        $posts = Post::with('user', 'category.parent') // Eager load để tối ưu truy vấn
                       ->latest()                      // Sắp xếp mới nhất lên đầu
                       ->paginate(9);                 // Phân trang, ví dụ 9 bài một trang


        return view('user.user_dashboard', compact('posts'));
    }
}
