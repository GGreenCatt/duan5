<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
| Đây là nơi bạn có thể đăng ký các route cho ứng dụng của mình.
| Các route này sẽ được tải bởi RouteServiceProvider và tất cả
| sẽ được gán cho nhóm middleware "web".
|----------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', function () {
    return view('welcome');
});

// Trang dashboard cho người dùng đã đăng nhập
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes cho bài đăng
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('posts.index');
        Route::get('/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('/list', [PostController::class, 'listPosts'])->name('posts.list');
        Route::get('/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::delete('/{post}/delete-banner', [PostController::class, 'deleteBanner'])->name('posts.deleteBanner');
        Route::post('/{post}/delete-gallery', [PostController::class, 'deleteGallery'])->name('posts.deleteGallery');
    });

    // Routes cho trang chỉnh sửa và xóa hồ sơ người dùng
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Trang danh sách bài đăng
    Route::get('/listdanhsach', [PostController::class, 'listPosts'])->name('listdanhsach');
});

// Routes cho xác thực người dùng
require __DIR__.'/auth.php';
