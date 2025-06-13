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

    Route::prefix('posts')->name('posts.')->group(function () { // Thêm name('posts.') cho group
        // Route mới cho posts.index, trỏ đến PostController@listPosts
        // URL sẽ là /posts
        Route::get('/', [PostController::class, 'listPosts'])->name('index');

        // Chỉ Admin mới có thể truy cập các route tạo bài viết
        Route::middleware('admin')->group(function () {
            Route::get('/create', [PostController::class, 'create'])->name('create');
            Route::post('/', [PostController::class, 'store'])->name('store');
        });

        // Các route khác của posts
        Route::get('/export', [PostController::class, 'exportPosts'])->name('export')->middleware('admin');
        Route::get('/list', [PostController::class, 'listPosts'])->name('list'); // Route này vẫn giữ nguyên, URL /posts/list
        Route::get('/{post}', [PostController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');

        Route::delete('/{post}/delete-banner', [PostController::class, 'deleteBanner'])->name('deleteBanner');
        Route::post('/{post}/delete-gallery', [PostController::class, 'deleteGallery'])->name('deleteGallery');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/listdanhsach', [PostController::class, 'listPosts'])->name('posts.listdanhsach'); // Giữ nguyên route này nếu bạn vẫn dùng
});

require __DIR__.'/auth.php';
