<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category; // <-- Đảm bảo bạn đã `use`
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        // 1. Lấy 3 bài viết trending (mới nhất)
        $trendingPosts = Post::with(['category.parent', 'user'])
                           ->orderBy('created_at', 'desc')
                           ->take(3)
                           ->get();

        // 2. Lấy ID của 3 bài trending đó
        $trendingIds = $trendingPosts->pluck('id');

        // 3. Lấy các bài viết còn lại (phân trang, 9 bài/trang)
        $posts = Post::with(['category.parent', 'user'])
                         ->whereNotIn('id', $trendingIds)
                         ->orderBy('created_at', 'desc')
                         ->paginate(9); // 9 bài cho lưới (grid)

        // 4. Lấy bài cho mục "Công nghệ" (Lấy 5 bài)
        $congNghePosts = Post::with(['category.parent', 'user'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Công nghệ')
                      ->orWhereHas('parent', function ($q) {
                          $q->where('name', 'Công nghệ');
                      });
            })
            ->whereNotIn('id', $trendingIds)
            ->orderBy('created_at', 'desc')
            ->take(5) 
            ->get();
        
        // 5. Lấy bài cho mục "Ngân Hàng" (Lấy 5 bài)
        $nganHangPosts = Post::with(['category.parent', 'user'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Ngân Hàng')
                      ->orWhereHas('parent', function ($q) {
                          $q->where('name', 'Ngân Hàng');
                      });
            })
            ->whereNotIn('id', $trendingIds)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 6. Lấy danh sách danh mục (cho thanh điều hướng ngang)
        //    (Lấy danh mục cha và các danh mục con phổ biến)
        $categories = Category::whereNull('parent_id')
                              ->with('children') 
                              ->orderBy('name', 'asc')
                              ->get();
        
        // 7. Gửi tất cả biến tới view
        return view('guest.index', compact( // Sửa 'user.user_dashboard' thành 'guest.index'
            'trendingPosts',
            'posts',
            'congNghePosts',
            'nganHangPosts',
            'categories'
        ));
    }
    public function allCategories()
    {
        // Lấy tất cả danh mục cha
        // và eager load các danh mục con của chúng, đồng thời đếm số bài viết cho mỗi danh mục con
        $parentCategories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->withCount('posts')->orderBy('name', 'asc');
            }])
            ->withCount('posts') // Đếm cả bài viết của chính danh mục cha
            ->orderBy('name', 'asc')
            ->get();

        return view('guest.categories', compact('parentCategories'));
    }
}