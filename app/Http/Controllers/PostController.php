<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\PostsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    public function listPosts(Request $request)
    {
        Gate::authorize('admin');

        $parentCategories = Category::whereNull('parent_id')->with('children')->orderBy('name', 'asc')->get();
        $childCategories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->get();
        $query = Post::with(['category', 'user'])->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('child_category_id')) {
            $query->where('category_id', $request->child_category_id);
        } elseif ($request->filled('parent_category_id')) {
            $parentCategory = Category::with('children')->find($request->parent_category_id);
            if ($parentCategory) {
                $categoryIds = $parentCategory->children->pluck('id')->all();
                if(!empty($categoryIds)) {
                    $query->whereIn('category_id', $categoryIds);
                }
            }
        }
        $posts = $query->paginate(10)->withQueryString();
        $totalPosts = Post::count();
        $postsThisMonth = Post::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        return view('posts.listdanhsach', compact('posts', 'parentCategories', 'childCategories', 'totalPosts', 'postsThisMonth'));
    }

    /**
     * Hiển thị bài viết theo danh mục (CHỈ DÀNH CHO ADMIN).
     */
    public function postsByCategory(Category $category)
    {
        Gate::authorize('admin');

        $parentCategories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();
        $childCategories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->get();
        $categoryIds = $category->children()->pluck('id')->push($category->id);
        $posts = Post::with(['category', 'user'])->whereIn('category_id', $categoryIds)->orderBy('created_at', 'desc')->paginate(12);
        $categoryName = $category->name;
        $totalPosts = Post::count();
        $postsThisMonth = Post::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        return view('posts.listdanhsach', compact('posts', 'parentCategories', 'childCategories', 'categoryName', 'totalPosts', 'postsThisMonth'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Post::class);

        $parentCategories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();
        $allCategories = Category::orderBy('name', 'asc')->get();
        return view('posts.create', compact('parentCategories', 'allCategories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $validatedData = $request->validate([
            'title' => 'required|max:100',
            'short_description' => 'required|max:200',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gallery_images' => 'nullable|array|min:2|max:5',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $validatedData['title'];
        $post->short_description = $validatedData['short_description'];
        $post->content = $validatedData['content'];
        $post->category_id = $validatedData['category_id'];

        if ($request->hasFile('banner_image')) {
            $post->banner_image = $request->file('banner_image')->store('post_banners', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('post_gallery', 'public');
            }
            $post->gallery_images = $galleryPaths;
        } else {
            $post->gallery_images = [];
        }

        $post->save();

        return redirect()->route('admin.posts.list')->with('success', 'Bài viết đã được tạo thành công.');
    }
    
    public function show(Post $post)
    {
        $viewed = session()->get('viewed_posts', []);

        if (!in_array($post->id, $viewed)) {
            $post->increment('views');
            session()->push('viewed_posts', $post->id);
        }

        // Eager load relationships for performance
        $post->load([
            'category.parent', 
            'user', 
            'interactions',
            // Load only top-level comments
            'comments' => function ($query) {
                $query->whereNull('parent_id')
                      ->where(function ($q) {
                          $q->where('status', 'approved');
                          if (Auth::check()) {
                              $q->orWhere(function ($subQuery) {
                                  $subQuery->where('user_id', Auth::id())
                                           ->where('status', 'pending');
                              });
                          }
                      })
                      ->with([
                          'user', 
                          'interactions', 
                          'replies' => function($replyQuery) {
                              $replyQuery->where('status', 'approved'); // Only approved replies for everyone
                              if (Auth::check()) {
                                  $replyQuery->orWhere(function ($subQuery) {
                                      $subQuery->where('user_id', Auth::id())
                                               ->where('status', 'pending');
                                  });
                              }
                              $replyQuery->with('user', 'interactions', 'replies');
                          }
                      ])->orderBy('created_at', 'desc');
            }
        ]);

        $relatedPosts = Post::where('category_id', $post->category_id)
                            ->where('id', '!=', $post->id)
                            ->orderBy('created_at', 'desc')
                            ->take(4)
                            ->get();
        
        return view('guest.show', compact('post', 'relatedPosts'));
    }

    public function showForAdmin(Post $post)
    {
        Gate::authorize('admin');

        $post->load([
            'category.parent', 
            'user', 
            'likes',
            'dislikes',
            'comments' => function ($query) {
                $query->whereNull('parent_id')->with([
                    'user', 
                    'interactions', 
                    'replies' => function($replyQuery) {
                        $replyQuery->with('user', 'interactions', 'replies'); // Recursive load
                    }
                ])->orderBy('created_at', 'desc');
            }
        ]);
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->get();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'short_description' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            // ===== BỔ SUNG VALIDATION CHO CÁC TRƯỜNG ẨN =====
            'deleted_banner' => 'nullable|boolean',
            'deleted_gallery_images' => 'nullable|array',
            'deleted_gallery_images.*' => 'string',
        ]);

        $post->title = $validatedData['title'];
        $post->short_description = $validatedData['short_description'];
        $post->content = $validatedData['content'];
        $post->category_id = $validatedData['category_id'];

        // Xử lý banner
        if ($request->input('deleted_banner') == '1' && $post->banner_image) {
            Storage::disk('public')->delete($post->banner_image);
            $post->banner_image = null;
        }
        if ($request->hasFile('banner_image')) {
            if ($post->banner_image) {
                Storage::disk('public')->delete($post->banner_image);
            }
            $post->banner_image = $request->file('banner_image')->store('post_banners', 'public');
        }

        // ===== LOGIC SỬA LỖI XÓA ẢNH THƯ VIỆN =====
        $currentGallery = $post->gallery_images ?? [];

        // 1. Xử lý các ảnh được đánh dấu để xóa
        if ($request->has('deleted_gallery_images')) {
            $imagesToDelete = $request->input('deleted_gallery_images');
            // Xóa file vật lý
            foreach ($imagesToDelete as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            // Loại bỏ các ảnh đã xóa khỏi mảng hiện tại
            $currentGallery = array_diff($currentGallery, $imagesToDelete);
        }

        // 2. Thêm các ảnh mới được upload (nếu có)
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $currentGallery[] = $image->store('post_gallery', 'public');
            }
        }
        
        // 3. Cập nhật lại mảng gallery cho bài viết và re-index lại array
        $post->gallery_images = array_values($currentGallery); 
        // ===============================================

        $post->save();

        return redirect()->route('admin.posts.list')->with('success', 'Bài viết đã được cập nhật thành công.');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->banner_image) { Storage::disk('public')->delete($post->banner_image); }
        if (is_array($post->gallery_images)) {
            foreach ($post->gallery_images as $image) { Storage::disk('public')->delete($image); }
        }
        $post->delete();
        return redirect()->route('admin.posts.list')->with('success', 'Bài viết đã được xóa thành công.');
    }

    public function bulkDestroy(Request $request)
    {
        Gate::authorize('admin');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:posts,id',
        ]);
        $postIds = $request->input('ids');
        $posts = Post::whereIn('id', $postIds)->get();
        foreach ($posts as $post) {
            if ($post->banner_image) { Storage::disk('public')->delete($post->banner_image); }
            if (is_array($post->gallery_images)) {
                foreach ($post->gallery_images as $image) { Storage::disk('public')->delete($image); }
            }
        }
        Post::destroy($postIds);
        return redirect()->route('admin.posts.list')->with('success', count($postIds) . ' bài viết đã được xóa thành công.');
    }

    public function exportPosts()
    {
        Gate::authorize('admin');

        return Excel::download(new PostsExport, 'danh-sach-bai-viet.xlsx');
    }
}