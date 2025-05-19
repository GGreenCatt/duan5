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

        // Thống kê số liệu tổng
        $totalPosts = $posts->count();
        $totalViews = $posts->sum('views'); // Nếu bạn có cột 'views' cho lượt tương tác

        // Thống kê bài đăng trong tuần và tháng
        $postsThisWeek = Post::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $postsThisMonth = Post::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();

        // Lấy dữ liệu bài đăng theo từng ngày trong tháng
        $dailyPosts = Post::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Đảm bảo dữ liệu theo ngày đủ cho cả tháng
        $dailyLabels = [];
        $dailyData = [];
        for ($i = 1; $i <= Carbon::now()->daysInMonth; $i++) {
            $date = Carbon::now()->startOfMonth()->addDays($i - 1)->format('Y-m-d');
            $dailyLabels[] = $date;
            $dailyData[] = $dailyPosts[$date] ?? 0;
        }

        // Lấy dữ liệu bài đăng theo tuần trong năm
        $weeklyPosts = Post::selectRaw('WEEK(created_at) as week, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('week')
            ->pluck('count', 'week')
            ->toArray();

        $weeklyLabels = range(1, 52); // Danh sách 52 tuần
        $weeklyData = array_fill(0, 52, 0);
        foreach ($weeklyPosts as $week => $count) {
            $weeklyData[$week - 1] = $count;
        }

        // Lấy dữ liệu bài đăng theo tháng trong năm
        $monthlyPosts = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Đảm bảo dữ liệu có đủ 12 tháng với giá trị mặc định là 0
        $months = [
            'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5',
            'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9',
            'Tháng 10', 'Tháng 11', 'Tháng 12'
        ];
        $monthlyData = array_fill(0, 12, 0);
        foreach ($monthlyPosts as $month => $count) {
            $monthlyData[$month - 1] = $count;
        }

        // Trả về view 'posts.index' thay vì 'dashboard.index'
        return view('posts.index', compact(
            'posts',
            'totalPosts',
            'totalViews',
            'postsThisWeek',
            'postsThisMonth',
            'dailyLabels',
            'dailyData',
            'weeklyLabels',
            'weeklyData',
            'months',
            'monthlyData'
        ));
    }
}
