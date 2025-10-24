<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $trendingPosts = Post::with('category', 'user')->orderBy('created_at', 'desc')->take(3)->get();
        $posts = Post::with('category', 'user')->orderBy('created_at', 'desc')->paginate(9);
        $categories = Category::withCount('posts')->whereNull('parent_id')->orderBy('name', 'asc')->get();

        $congNghePosts = Post::whereHas('category', function($q){
            $q->where('slug', 'cong-nghe')->orWhereHas('parent', fn($q) => $q->where('slug', 'cong-nghe'));
        })->latest()->take(3)->get();
        
        $nganHangPosts = Post::whereHas('category', function($q){
            $q->where('slug', 'ngan-hang')->orWhereHas('parent', fn($q) => $q->where('slug', 'ngan-hang'));
        })->latest()->take(3)->get();

        return view('guest.index', compact('trendingPosts', 'posts', 'categories', 'congNghePosts', 'nganHangPosts'));
    }

    /**
     * ===== TÍNH NĂNG MỚI: Hiển thị trang danh sách tất cả danh mục =====
     */
    public function allCategories()
    {
        // Lấy tất cả danh mục cha và các danh mục con tương ứng
        $parentCategories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->withCount('posts')->orderBy('name', 'asc');
            }])
            ->withCount('posts')
            ->orderBy('name', 'asc')
            ->get();

        // Trả về view 'guest.categories' mà bạn đã tạo
        return view('guest.categories', compact('parentCategories'));
    }
}