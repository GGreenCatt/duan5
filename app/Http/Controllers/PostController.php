<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
        // Hiển thị danh sách bài viết
    public function index()
    {
        $posts = Post::all();
        $totalPosts = $posts->count();
        $postsThisWeek = Post::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $postsThisMonth = Post::whereMonth('created_at', Carbon::now()->month)->count();

        // Dữ liệu cho biểu đồ
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->format('F');
        });

        $monthlyData = $months->map(function ($month, $index) {
            return Post::whereMonth('created_at', $index + 1)->count();
        });

        return view('posts.index', compact('posts', 'totalPosts', 'postsThisWeek', 'postsThisMonth', 'months', 'monthlyData'));
    }

    // Form tạo bài viết
    public function create()
    {
        return view('posts.create');
    }

    // Lưu bài viết
    // Lưu bài viết
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'content' => 'required|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Lưu banner ảnh
        $banner_image = $request->hasFile('banner_image')
            ? $request->file('banner_image')->store('posts/banners', 'public')
            : null;

        // Lưu gallery ảnh
        $gallery_images = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $gallery_images[] = $image->store('posts/gallery', 'public');
            }
        }

        // Tạo bài viết mới
        Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'short_description' => $request->short_description,
            'content' => $request->content,
            'banner_image' => $banner_image,
            'gallery_images' => json_encode($gallery_images),
        ]);

        // Chuyển hướng về trang listPosts (đã có thống kê)
        return redirect()->route('listdanhsach')->with('success', 'Bài viết đã được tạo.');
    }


    // Form sửa bài viết
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Cập nhật bài viết
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'content' => 'required|string',
        ]);

        // Xử lý banner image
        if ($request->hasFile('banner_image')) {
            if ($post->banner_image) {
                Storage::disk('public')->delete($post->banner_image);
            }
            $post->banner_image = $request->file('banner_image')->store('posts/banners', 'public');
        }

        // Xử lý gallery images
        if ($request->hasFile('gallery_images')) {
            if ($post->gallery_images) {
                foreach (json_decode($post->gallery_images) as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $gallery_images = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery_images[] = $image->store('posts/gallery', 'public');
            }
            $post->gallery_images = json_encode($gallery_images);
        }

        // Cập nhật nội dung bài viết
        $post->update([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'content' => $request->content,
        ]);

        return redirect()->route('posts.index')->with('success', 'Bài viết đã được cập nhật.');
    }
    public function list()
{
    // Lấy danh sách bài đăng
    $posts = Post::with('user')->get();

    // Trả về trang listdanhsach.blade.php
    return view('posts.listdanhsach', compact('posts'));
}
    // Trong PostController hoặc DashboardController
    public function listPosts()
    {
        $posts = Post::with('user')->get();
        return view('posts.listdanhsach', compact('posts'));
    }

    // Xóa bài viết
    public function destroy(Post $post)
    {
        if ($post->banner_image) {
            Storage::disk('public')->delete($post->banner_image);
        }

        if ($post->gallery_images) {
            foreach (json_decode($post->gallery_images) as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $post->delete();
        return redirect()->route('posts.list')->with('success', 'Bài viết đã được xóa.');
    }

    // Hiển thị bài viết
    public function show(Post $post)
    {
        $galleryImages = $post->gallery_images ? json_decode($post->gallery_images, true) : [];
        return view('posts.show', compact('post', 'galleryImages'));
    }
    public function deleteBanner(Post $post)
    {
        if ($post->banner_image) {
            Storage::delete('public/' . $post->banner_image);
            $post->banner_image = null;
            $post->save();
        }

        return response()->json(['success' => true]);
    }

    // Xóa ảnh thư viện
    public function deleteGallery(Request $request, Post $post)
    {
        $imageToDelete = $request->image;
        $galleryImages = json_decode($post->gallery_images, true);

        if (($key = array_search($imageToDelete, $galleryImages)) !== false) {
            // Xóa ảnh từ thư mục lưu trữ
            Storage::delete('public/' . $imageToDelete);

            // Xóa ảnh khỏi mảng
            unset($galleryImages[$key]);
            $post->gallery_images = json_encode(array_values($galleryImages)); // Cập nhật lại mảng
            $post->save();
        }

        return response()->json(['success' => true]);
    }

}
