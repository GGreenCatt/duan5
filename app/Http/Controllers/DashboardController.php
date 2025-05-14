<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Hiển thị trang dashboard
    public function index()
    {
        // Lấy tất cả bài viết
        $posts = Post::all();

        // Thống kê số liệu
        $totalPosts = Post::count();
        $postsThisWeek = Post::where('created_at', '>=', now()->startOfWeek())->count();
        $postsThisMonth = Post::where('created_at', '>=', now()->startOfMonth())->count();

        // Lấy dữ liệu số bài đăng theo từng tháng
        $monthlyPosts = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month');

        // Đảm bảo dữ liệu có đủ 12 tháng với giá trị mặc định là 0
        $monthlyData = array_fill(1, 12, 0);
        foreach ($monthlyPosts as $month => $count) {
            $monthlyData[$month] = $count;
        }

        // Danh sách tên tháng
        $months = [
        ];

        // Trả về view 'posts.index' thay vì 'dashboard.index'
        return view('posts.index', compact(
            'posts',
            'totalPosts',
            'postsThisWeek',
            'postsThisMonth',
            'monthlyData',
            'months'
        ));
    }


}
