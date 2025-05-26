<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;


Route::resource('categories', CategoryController::class)->except(['show']);

// ✅ Bổ sung route hiển thị danh mục và các bài viết liên quan
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
// web.php
Route::delete('/categories/{category}/delete-image', [CategoryController::class, 'deleteImage'])->name('categories.deleteImage');

// Trang chủ
Route::get('/', function () {
    return view('welcome');
});

// Nhóm route dành cho người dùng đã đăng nhập và xác thực
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý bài viết
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('posts.index');
        Route::get('/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('/export', [PostController::class, 'exportPosts'])->name('posts.export'); // Route mới cho export
        Route::get('/list', [PostController::class, 'listPosts'])->name('posts.list');
        Route::get('/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

        Route::delete('/{post}/delete-banner', [PostController::class, 'deleteBanner'])->name('posts.deleteBanner');
        Route::post('/{post}/delete-gallery', [PostController::class, 'deleteGallery'])->name('posts.deleteGallery');
    });

    // Hồ sơ người dùng
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Danh sách bài viết tổng hợp
    Route::get('/listdanhsach', [PostController::class, 'listPosts'])->name('posts.listdanhsach'); // Đổi tên route cho nhất quán
});

// Xác thực
require __DIR__.'/auth.php';
