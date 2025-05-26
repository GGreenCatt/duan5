<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\PostsExport; // Thêm dòng này
use Maatwebsite\Excel\Facades\Excel; // Thêm dòng này
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Hiển thị danh sách bài viết (dashboard)
public function index()
{
    $totalPosts = Post::count();

    $postsThisWeek = Post::whereBetween('created_at', [
        now()->startOfWeek(),
        now()->endOfWeek()
    ])->count();

    $postsThisMonth = Post::whereMonth('created_at', now()->month)->count();

    // Dữ liệu cho biểu đồ theo tháng
    $monthlyData = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->groupBy('month')
        ->pluck('count', 'month')->toArray();

    $months = [];
    $monthlyCounts = [];
    for ($i = 1; $i <= 12; $i++) {
        $months[] = "Tháng $i";
        $monthlyCounts[] = $monthlyData[$i] ?? 0;
    }

    // Dữ liệu theo tuần
    $weeklyData = Post::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
        ->whereYear('created_at', now()->year)
        ->groupBy('week')
        ->pluck('count', 'week')->toArray();

    $weeklyLabels = [];
    $weeklyCounts = [];
    for ($i = 1; $i <= 52; $i++) {
        $weeklyLabels[] = "Tuần $i";
        $weeklyCounts[] = $weeklyData[$i] ?? 0;
    }

    // Dữ liệu theo ngày (trong 7 ngày gần nhất)
    $dailyData = Post::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->whereDate('created_at', '>=', now()->subDays(6)->toDateString())
        ->groupBy('date')
        ->pluck('count', 'date')->toArray();

    $dailyLabels = [];
    $dailyCounts = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->toDateString();
        $dailyLabels[] = \Carbon\Carbon::parse($date)->format('d/m');
        $dailyCounts[] = $dailyData[$date] ?? 0;
    }

    return view('posts.index', compact(
        'totalPosts',
        'postsThisWeek',
        'postsThisMonth',
        'months',
        'monthlyCounts',
        'weeklyLabels',
        'weeklyCounts',
        'dailyLabels',
        'dailyCounts'
    ));
}


    // Form tạo bài viết
    public function create()
    {
        // Lấy danh mục cha (parent_id = NULL)
        $parentCategories = Category::whereNull('parent_id')->get();

        // Lấy tất cả danh mục để lọc danh mục con trong view
        $allCategories = Category::all();

        // Truyền dữ liệu vào view
        return view('posts.create', compact('parentCategories', 'allCategories'));
    }

    // Lưu bài viết mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:100', function ($attribute, $value, $fail) {
                if (trim($value) === '') {
                    $fail('Không được nhập chỉ khoảng trắng.');
                }
            }],
            'short_description' => ['required', 'string', 'max:200', function ($attribute, $value, $fail) {
                if (trim($value) === '') {
                    $fail('Không được nhập chỉ khoảng trắng.');
                }
            }],
            'content' => ['required', 'string', 'max:1000', function ($attribute, $value, $fail) {
                if (trim(strip_tags($value)) === '') {
                    $fail('Nội dung không được để trống hoặc chỉ chứa thẻ HTML rỗng.');
                }
            }],
            'category_id' => 'required|exists:categories,id',
        ], [
            'title.max' => 'Tiêu đề không được vượt quá 100 ký tự.',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 200 ký tự.',
            'content.max' => 'Nội dung không được vượt quá 1000 ký tự.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' =>'Danh mục không hợp lệ.',
        ]);

        $banner_image = $request->hasFile('banner_image')
            ? $request->file('banner_image')->store('posts/banners', 'public')
            : null;

        $gallery_images = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $gallery_images[] = $image->store('posts/gallery', 'public');
            }
        }

        Post::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'short_description' => $request->short_description,
            'content' => $request->content,
            'banner_image' => $banner_image,
            'gallery_images' => json_encode($gallery_images),
        ]);
        dd($request->input('content'));
        return redirect()->route('listdanhsach')->with('success', 'Bài viết đã được tạo.');
    }

    // Trang chỉnh sửa
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    // Cập nhật bài viết
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($post->banner_image) {
                Storage::disk('public')->delete($post->banner_image);
            }
            $post->banner_image = $request->file('banner_image')->store('posts/banners', 'public');
        }

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

        $post->update([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('posts.list')->with('success', 'Cập nhật bài viết thành công!');
    }

    // Danh sách bài viết
    public function list()
    {
        $posts = Post::with('category.parent', 'user')->get();
        $categories = Category::whereNotNull('parent_id')->get();

        return view('your-view-name', compact('posts', 'categories'));
    }

public function listPosts()
{
    // Lấy tất cả bài viết, eager load category cha và user, sắp xếp mới nhất lên đầu
    $posts = Post::with(['category.parent', 'user'])->latest()->get();

    // Lấy danh mục cha
    $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

    // Lấy danh mục con
    $childCategories = Category::whereNotNull('parent_id')->orderBy('name')->get();

    // Truyền dữ liệu sang view
    return view('posts.listdanhsach', compact('posts', 'parentCategories', 'childCategories'));
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

    public function deleteGallery(Request $request, Post $post)
    {
        $imageToDelete = $request->image;
        $galleryImages = json_decode($post->gallery_images, true);

        if (($key = array_search($imageToDelete, $galleryImages)) !== false) {
            Storage::delete('public/' . $imageToDelete);
            unset($galleryImages[$key]);
            $post->gallery_images = json_encode(array_values($galleryImages));
            $post->save();
        }

        return response()->json(['success' => true]);
    }

    // Phương thức mới để export Excel
    public function exportPosts()
    {
        return Excel::download(new PostsExport, 'danh_sach_bai_dang.xlsx');
    }
}
