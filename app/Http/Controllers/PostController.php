<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\PostsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Hiển thị danh sách bài viết (CHỈ DÀNH CHO ADMIN).
     */
    public function listPosts(Request $request)
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();
        $childCategories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->get();
        $posts = Post::with(['category', 'user'])->orderBy('created_at', 'desc')->paginate(12);
        return view('posts.listdanhsach', compact('posts', 'parentCategories', 'childCategories'));
    }

    /**
     * Hiển thị bài viết theo danh mục (CHỈ DÀNH CHO ADMIN).
     */
    public function postsByCategory(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();
        $childCategories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->get();
        $categoryIds = $category->children()->pluck('id')->push($category->id);
        $posts = Post::with(['category', 'user'])->whereIn('category_id', $categoryIds)->orderBy('created_at', 'desc')->paginate(12);
        $categoryName = $category->name;
        return view('posts.listdanhsach', compact('posts', 'parentCategories', 'childCategories', 'categoryName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ===== ĐÃ CẬP NHẬT: Gửi 2 biến cho view =====
        // 1. Chỉ lấy các danh mục cha cho dropdown đầu tiên
        $parentCategories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();
        // 2. Lấy tất cả danh mục để JS lọc ra danh mục con
        $allCategories = Category::orderBy('name', 'asc')->get();
        
        return view('posts.create', compact('parentCategories', 'allCategories'));
        // ===============================================
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:100',
            'short_description' => 'required|max:200',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gallery_images' => 'nullable|array|min:2|max:5',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $post = new Post($validatedData);
        $post->user_id = Auth::id();

        if ($request->hasFile('banner_image')) {
            $post->banner_image = $request->file('banner_image')->store('post_banners', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $image->store('post_gallery', 'public');
            }
            $post->gallery_images = $gallery;
        }

        $post->save();

        return redirect()->route('posts.list')->with('success', 'Bài viết đã được tạo thành công.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Tải các quan hệ cần thiết
        $post->load(['category.parent', 'user']);
        
        // Lấy các bài viết liên quan
        $relatedPosts = Post::where('category_id', $post->category_id)
                            ->where('id', '!=', $post->id)
                            ->orderBy('created_at', 'desc')
                            ->take(4)
                            ->get();
                            
        // ===== ĐÃ CẬP NHẬT: Bỏ json_decode và biến $galleryImages =====
        // Biến $post->gallery_images đã là một array (do Model cast) và sẽ được dùng trực tiếp trong view.
        return view('posts.show', compact('post', 'relatedPosts'));
        // =============================================================
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // View edit.blade.php cần biến $categories, chứa danh sách phẳng
        $categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->get();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'short_description' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $post->fill($validatedData);

        if ($request->hasFile('banner_image')) {
            if ($post->banner_image) {
                Storage::disk('public')->delete($post->banner_image);
            }
            $post->banner_image = $request->file('banner_image')->store('post_banners', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = $post->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $image->store('post_gallery', 'public');
            }
            $post->gallery_images = $gallery;
        }

        $post->save();

        return redirect()->route('posts.list')->with('success', 'Bài viết đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->banner_image) {
            Storage::disk('public')->delete($post->banner_image);
        }
        if (is_array($post->gallery_images)) {
            foreach ($post->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $post->delete();
        return redirect()->route('posts.list')->with('success', 'Bài viết đã được xóa thành công.');
    }

    /**
     * Remove the banner image from the specified resource.
     */
    public function deleteBanner(Post $post)
    {
        if ($post->banner_image) {
            Storage::disk('public')->delete($post->banner_image);
            $post->banner_image = null;
            $post->save();
            return response()->json(['success' => true, 'message' => 'Ảnh banner đã được xóa.']);
        }
        return response()->json(['success' => false, 'message' => 'Không có ảnh banner để xóa.'], 404);
    }

    /**
     * Remove a gallery image from the specified resource.
     */
    public function deleteGallery(Request $request, Post $post)
    {
        $imagePath = $request->input('image');
        if ($post->gallery_images && is_array($post->gallery_images)) {
            $gallery = $post->gallery_images;
            if (($key = array_search($imagePath, $gallery)) !== false) {
                Storage::disk('public')->delete($imagePath);
                unset($gallery[$key]);
                $post->gallery_images = array_values($gallery); 
                $post->save();
                return response()->json(['success' => true, 'message' => 'Ảnh trong thư viện đã được xóa.']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh hoặc có lỗi xảy ra.'], 404);
    }

    /**
     * Export posts to an Excel file.
     */
    public function exportPosts()
    {
        return Excel::download(new PostsExport, 'danh-sach-bai-viet.xlsx');
    }
}