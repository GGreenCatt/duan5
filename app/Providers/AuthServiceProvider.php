<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate for managing users (only 'quản trị')
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'Admin';
        });

        // Gate for managing categories (only 'quản trị' and 'biên tập viên')
        Gate::define('manage-categories', function (User $user) {
            return in_array($user->role, ['Admin', 'Editor']);
        });

        // Gate for managing comments (only 'quản trị' and 'biên tập viên')
        Gate::define('manage-comments', function (User $user) {
            return in_array($user->role, ['Admin', 'Editor']);
        });

        // Gate for managing posts (only 'quản trị' and 'biên tập viên')
        Gate::define('manage-posts', function (User $user) {
            return in_array($user->role, ['Admin', 'Editor']);
        });

        // Gate for VIP access (Vip, 'quản trị', 'biên tập viên')
        Gate::define('vip-access', function (User $user) {
            return in_array($user->role, ['Admin', 'Editor', 'Vip']);
        });
    }
}
