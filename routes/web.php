<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserPostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
// Gộp các route của khách cho gọn
Route::get('/', [UserDashboardController::class, 'index'])->name('guest.home');
Route::get('/about-us', function () { return view('guest.about'); })->name('about');
Route::get('/contact', [UserDashboardController::class, 'contact'])->name('guest.contact');
Route::get('/posts', [UserPostController::class, 'index'])->name('guest.posts.index');
Route::get('/categories', [UserDashboardController::class, 'allCategories'])->name('guest.categories'); // ĐÂY LÀ ROUTE CẦN CÓ

// Route chi tiết
Route::get('/posts/category/{category:slug}', [UserPostController::class, 'postsByCategory'])->name('guest.posts.by_category');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* --- Admin Only Routes --- */
    Route::middleware('admin')->prefix('admin')->group(function() {
        // Category Management
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::delete('/categories/{category}/delete-image', [CategoryController::class, 'deleteImage'])->name('categories.deleteImage');

        // Post Management (Admin)
        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('/', [PostController::class, 'listPosts'])->name('list');
            Route::get('/create', [PostController::class, 'create'])->name('create');
            Route::post('/', [PostController::class, 'store'])->name('store');
            Route::get('/export', [PostController::class, 'exportPosts'])->name('export');
            Route::delete('/bulk-delete', [PostController::class, 'bulkDestroy'])->name('bulkDestroy');
            Route::get('/category/{category}', [PostController::class, 'postsByCategory'])->name('by_category');
            
            // Routes with {post} parameter
            Route::get('/{post}/show', [PostController::class, 'showForAdmin'])->name('show_for_admin');
            Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
            Route::put('/{post}', [PostController::class, 'update'])->name('update');
            Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
            Route::delete('/{post}/delete-banner', [PostController::class, 'deleteBanner'])->name('deleteBanner');
            Route::delete('/{post}/delete-gallery', [PostController::class, 'deleteGallery'])->name('deleteGallery');
        });
    });
});

require __DIR__.'/auth.php';