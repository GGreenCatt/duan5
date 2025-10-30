<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserPostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostInteractionController;
use App\Http\Controllers\CommentInteractionController;
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
Route::post('/contact', [UserDashboardController::class, 'sendContactEmail'])->name('guest.contact.send');
Route::get('/posts', [UserPostController::class, 'index'])->name('guest.posts.index');
Route::get('/categories', [UserDashboardController::class, 'allCategories'])->name('guest.categories'); // ĐÂY LÀ ROUTE CẦN CÓ

// Route chi tiết
Route::get('/posts/category/{category:slug}', [UserPostController::class, 'postsByCategory'])->name('guest.posts.by_category');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/banned', function () {
    return view('banned');
})->name('banned');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Comment Route
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    // Interaction Routes
    Route::post('/posts/interact', [PostInteractionController::class, 'store'])->name('posts.interact');
    Route::post('/comments/interact', [CommentInteractionController::class, 'store'])->name('comments.interact');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* --- Admin Only Routes --- */
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function() {
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

        // Comment Management (Admin)
        Route::get('/comments', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
        Route::put('/comments/{comment}/approve', [App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::put('/comments/{comment}/reject', [App\Http\Controllers\Admin\CommentController::class, 'reject'])->name('comments.reject');
        Route::delete('/comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');

        // User Management (Admin)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
            Route::get('/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
            Route::put('/{user}/ban', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('ban');
            Route::put('/{user}/unban', [App\Http\Controllers\Admin\UserController::class, 'unban'])->name('unban');
            Route::delete('/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
            Route::get('/{user}/comments', [App\Http\Controllers\Admin\UserController::class, 'comments'])->name('comments');
            Route::put('/{user}/update-role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('updateRole');
            Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'editUser'])->name('edit');
            Route::put('/{user}', [App\Http\Controllers\Admin\UserController::class, 'updateUser'])->name('update');
        });
    });
});

require __DIR__.'/auth.php';