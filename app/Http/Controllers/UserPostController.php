<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class UserPostController extends Controller
{
    /**
     * Hiển thị danh sách tất cả bài viết cho User.
     */
    public function index(Request $request)
    {
        // Lấy tất cả danh mục cha để hiển thị thanh lọc
        $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();

        // Lấy danh sách bài viết, sắp xếp mới nhất lên đầu, phân trang
        $posts = Post::with(['category.parent', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(9); // 9 bài/trang cho User

        // Trả về view dành riêng cho User
        return view('guest.post_list', compact('posts', 'categories')); // Sửa 'posts.user_post_index' thành 'guest.post_list'
    }

    /**
     * Hiển thị bài viết theo danh mục cho User.
     */
    public function postsByCategory(Category $category)
    {
        // Lấy tất cả danh mục cha để hiển thị thanh lọc
        $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();

        // Lấy ID của danh mục cha và các con của nó (nếu có)
        $categoryIds = $category->children()->pluck('id')->push($category->id);

        // Lọc bài viết
        $posts = Post::with(['category.parent', 'user'])
                        ->whereIn('category_id', $categoryIds)
                        ->orderBy('created_at', 'desc')
                        ->paginate(9); // 9 bài/trang cho User

        // Lấy tên danh mục để hiển thị tiêu đề
        $categoryName = $category->name;

        // Trả về view dành riêng cho User, truyền cả đối tượng $category
        return view('guest.post_list', compact('posts', 'categories', 'category', 'categoryName'));
    }
}