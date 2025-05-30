<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;


Route::resource('categories', CategoryController::class)->except(['show']);

Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::delete('/categories/{category}/delete-image', [CategoryController::class, 'deleteImage'])->name('categories.deleteImage');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    Route::prefix('posts')->group(function () {
        // Chỉ Admin mới có thể truy cập các route tạo bài viết
        Route::middleware('admin')->group(function () {
            Route::get('/create', [PostController::class, 'create'])->name('posts.create');
            Route::post('/', [PostController::class, 'store'])->name('posts.store');
        });

        // Các route khác của posts (xem, sửa, xóa,...) có thể giữ nguyên
        // hoặc bạn cũng có thể thêm middleware('admin') cho sửa, xóa nếu User không được tự sửa/xóa bài của mình
        // Hiện tại, việc sửa/xóa sẽ được kiểm soát trong Controller hoặc Policy sau này.
        Route::get('/export', [PostController::class, 'exportPosts'])->name('posts.export')->middleware('admin'); // Xuất excel cũng chỉ cho admin
        Route::get('/list', [PostController::class, 'listPosts'])->name('posts.list');
        Route::get('/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit'); // Cân nhắc thêm ->middleware('admin') nếu User không được sửa
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update'); // Cân nhắc thêm ->middleware('admin')
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy'); // Cân nhắc thêm ->middleware('admin')

        Route::delete('/{post}/delete-banner', [PostController::class, 'deleteBanner'])->name('posts.deleteBanner'); // Cân nhắc thêm ->middleware('admin')
        Route::post('/{post}/delete-gallery', [PostController::class, 'deleteGallery'])->name('posts.deleteGallery'); // Cân nhắc thêm ->middleware('admin')
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/listdanhsach', [PostController::class, 'listPosts'])->name('posts.listdanhsach');
});

require __DIR__.'/auth.php';
