<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\PostsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // ... (các phương thức khác như index, listPosts,...)

    public function create()
    {
        // Kiểm tra quyền: Chỉ Admin mới được tạo bài viết
        if (Auth::user()->role !== 'Admin') {
            // Chuyển hướng về user dashboard với thông báo lỗi nếu không phải Admin
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        $parentCategories = Category::whereNull('parent_id')->get();
        $allCategories = Category::all();
        return view('posts.create', compact('parentCategories', 'allCategories'));
    }

    public function store(Request $request)
    {
        // Kiểm tra quyền: Chỉ Admin mới được lưu bài viết
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

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
            'content' => ['required', 'string', 'max:3000', function ($attribute, $value, $fail) { // Tăng giới hạn content nếu cần
                if (trim(strip_tags($value)) === '') { // Kiểm tra nội dung sau khi loại bỏ HTML
                    $fail('Nội dung không được để trống hoặc chỉ chứa thẻ HTML rỗng.');
                }
            }],
            'category_id' => 'required|exists:categories,id',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Thêm webp, yêu cầu banner
            'gallery_images' => 'required|array|min:2|max:5', // Yêu cầu gallery, min 2 max 5
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Validate từng ảnh trong gallery
        ], [
            'title.max' => 'Tiêu đề không được vượt quá 100 ký tự.',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 200 ký tự.',
            'content.max' => 'Nội dung không được vượt quá 3000 ký tự.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' =>'Danh mục không hợp lệ.',
            'banner_image.required' => 'Vui lòng chọn ảnh banner.',
            'banner_image.image' => 'Banner phải là một tập tin hình ảnh.',
            'banner_image.mimes' => 'Banner phải có định dạng: jpeg, png, jpg, gif, webp.',
            'banner_image.max' => 'Banner không được vượt quá 2MB.',
            'gallery_images.required' => 'Vui lòng chọn ảnh thư viện.',
            'gallery_images.array' => 'Ảnh thư viện phải là một danh sách.',
            'gallery_images.min' => 'Phải chọn ít nhất 2 ảnh cho thư viện.',
            'gallery_images.max' => 'Chỉ được chọn tối đa 5 ảnh cho thư viện.',
            'gallery_images.*.image' => 'Mỗi mục trong thư viện phải là hình ảnh.',
            'gallery_images.*.mimes' => 'Ảnh thư viện phải có định dạng: jpeg, png, jpg, gif, webp.',
            'gallery_images.*.max' => 'Mỗi ảnh thư viện không được vượt quá 2MB.',
        ]);

        $banner_image_path = $request->file('banner_image')->store('posts/banners', 'public');

        $gallery_images_paths = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $gallery_images_paths[] = $image->store('posts/gallery', 'public');
            }
        }

        Post::create([
            'user_id' => Auth::id(), // Người tạo bài sẽ là Admin đang đăng nhập
            'category_id' => $request->category_id,
            'title' => $request->title,
            'short_description' => $request->short_description,
            'content' => $request->content, // CKEditor đã xử lý HTML
            'banner_image' => $banner_image_path,
            'gallery_images' => json_encode($gallery_images_paths),
        ]);
        // dd($request->input('content')); // Dòng này có thể gỡ bỏ sau khi debug
        return redirect()->route('posts.listdanhsach')->with('success', 'Bài viết đã được tạo thành công.'); // Sửa lại tên route listdanhsach nếu bạn đã đổi
    }

    // ... (các phương thức edit, update, destroy, show, deleteBanner, deleteGallery, exportPosts)
    // Bạn cũng nên xem xét việc thêm kiểm tra quyền cho các phương thức edit, update, destroy
    // nếu User thông thường không được phép sửa/xóa ngay cả bài của chính họ (trong trường hợp này thì không cần vì Admin quản lý hết)

    // Phương thức index và listPosts như cũ
    public function index()
    {
        // ... (logic hiện tại của bạn cho dashboard admin)
        $totalPosts = Post::count();

        $postsThisWeek = Post::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        $postsThisMonth = Post::whereMonth('created_at', now()->month)->count();

        $monthlyData = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        $months = [];
        $monthlyCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = "Tháng $i";
            $monthlyCounts[] = $monthlyData[$i] ?? 0;
        }

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

    public function listPosts()
    {
        $posts = Post::with(['category.parent', 'user'])->latest()->get();
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        $childCategories = Category::whereNotNull('parent_id')->orderBy('name')->get();
        return view('posts.listdanhsach', compact('posts', 'parentCategories', 'childCategories'));
    }


    public function edit(Post $post)
    {
        // Thêm kiểm tra: Chỉ Admin mới được sửa
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        // Thêm kiểm tra: Chỉ Admin mới được cập nhật
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }
        // Validate tương tự store, nhưng banner và gallery có thể không bắt buộc nếu không thay đổi
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'content' => ['required', 'string', 'max:3000', function ($attribute, $value, $fail) {
                if (trim(strip_tags($value)) === '') {
                    $fail('Nội dung không được để trống hoặc chỉ chứa thẻ HTML rỗng.');
                }
            }],
            'category_id' => 'required|exists:categories,id',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images' => 'nullable|array|max:5', // Không yêu cầu min khi update
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);


        if ($request->hasFile('banner_image')) {
            if ($post->banner_image && Storage::disk('public')->exists($post->banner_image)) {
                Storage::disk('public')->delete($post->banner_image);
            }
            $post->banner_image = $request->file('banner_image')->store('posts/banners', 'public');
        }

        // Xử lý gallery images: Xóa cũ nếu có upload mới, giữ lại nếu không upload gì
        if ($request->hasFile('gallery_images')) {
            // Xóa các ảnh gallery cũ (nếu có) chỉ khi có ảnh mới được upload
            if ($post->gallery_images) {
                $old_gallery = json_decode($post->gallery_images, true);
                if (is_array($old_gallery)) {
                    foreach ($old_gallery as $old_image) {
                        if (Storage::disk('public')->exists($old_image)) {
                            Storage::disk('public')->delete($old_image);
                        }
                    }
                }
            }

            $gallery_images_paths = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery_images_paths[] = $image->store('posts/gallery', 'public');
            }
            $post->gallery_images = json_encode($gallery_images_paths);
        }


        $post->title = $request->title;
        $post->short_description = $request->short_description;
        $post->content = $request->content;
        $post->category_id = $request->category_id;
        // user_id không thay đổi khi update bài viết
        $post->save();


        return redirect()->route('posts.listdanhsach')->with('success', 'Cập nhật bài viết thành công!');
    }


    public function destroy(Post $post)
    {
        // Thêm kiểm tra: Chỉ Admin mới được xóa
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        if ($post->banner_image) {
            Storage::disk('public')->delete($post->banner_image);
        }

        if ($post->gallery_images) {
            $gallery = json_decode($post->gallery_images, true);
            if(is_array($gallery)){
                foreach ($gallery as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $post->delete();
        return redirect()->route('posts.listdanhsach')->with('success', 'Bài viết đã được xóa.');
    }

    public function show(Post $post)
    {
        $galleryImages = $post->gallery_images ? json_decode($post->gallery_images, true) : [];
        return view('posts.show', compact('post', 'galleryImages'));
    }


    public function deleteBanner(Post $post)
    {
        // Chỉ Admin mới được xóa banner
        if (Auth::user()->role !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Không có quyền.'], 403);
        }

        if ($post->banner_image) {
            Storage::delete('public/' . $post->banner_image);
            $post->banner_image = null;
            $post->save();
            return response()->json(['success' => true, 'message' => 'Banner đã được xóa.']);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy banner.']);
    }

    public function deleteGallery(Request $request, Post $post)
    {
        // Chỉ Admin mới được xóa ảnh gallery
        if (Auth::user()->role !== 'Admin') {
             return response()->json(['success' => false, 'message' => 'Không có quyền.'], 403);
        }

        $imageToDelete = $request->input('image');
        $galleryImages = json_decode($post->gallery_images, true);

        if (is_array($galleryImages) && ($key = array_search($imageToDelete, $galleryImages)) !== false) {
            Storage::delete('public/' . $imageToDelete);
            unset($galleryImages[$key]);
            $post->gallery_images = json_encode(array_values($galleryImages)); // Re-index array
            $post->save();
            return response()->json(['success' => true, 'message' => 'Ảnh đã được xóa khỏi thư viện.']);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh hoặc lỗi khi xóa.']);
    }

    public function exportPosts()
    {
        // Chỉ Admin được export
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }
        return Excel::download(new PostsExport, 'danh_sach_bai_dang.xlsx');
    }
}
