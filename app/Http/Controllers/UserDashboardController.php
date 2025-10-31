<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class UserDashboardController extends Controller
{
    public function index()
    {
        $trendingPosts = Post::with(['category.parent', 'user' => fn($query) => $query->select('id', 'name')])->withCount('comments')->orderBy('created_at', 'desc')->take(3)->get();
        $posts = Post::with(['category.parent', 'user' => fn($query) => $query->select('id', 'name')])->withCount('comments')->orderBy('created_at', 'desc')->paginate(9);
        $categories = Category::withCount('posts')->whereNull('parent_id')->orderBy('name', 'asc')->get();

        $congNghePosts = Post::with(['user' => fn($query) => $query->select('id', 'name'), 'category.parent'])->withCount('comments')->whereHas('category', function($q){
            $q->where('slug', 'cong-nghe')->orWhereHas('parent', fn($q) => $q->where('slug', 'cong-nghe'));
        })->latest()->take(3)->get();
        
        $nganHangPosts = Post::with(['user' => fn($query) => $query->select('id', 'name'), 'category.parent'])->withCount('comments')->whereHas('category', function($q){
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

    /**
     * Hiển thị trang liên hệ.
     */
    public function contact()
    {
        return view('guest.contact');
    }

    /**
     * Xử lý gửi form liên hệ.
     */
    public function sendContactEmail(Request $request)
    {
        $validatedData = $request->validate([
            'full-name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Save to database
        Contact::create([
            'name' => $validatedData['full-name'],
            'email' => $validatedData['email'],
            'subject' => $validatedData['subject'],
            'message' => $validatedData['message'],
        ]);

        // Gửi email
        Mail::to(config('mail.from.address'))->send(new ContactFormMail($validatedData));

        return redirect()->route('guest.contact')->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}