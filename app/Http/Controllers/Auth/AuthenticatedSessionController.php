<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Thêm dòng này để gắn session thông báo chào mừng
        // Session này có thể được sử dụng ở bất kỳ view nào sau khi đăng nhập
        // để hiển thị thông báo một lần.
        session()->flash('login_success', true);

        // Kiểm tra vai trò của người dùng và điều hướng
        if (Auth::user()->role === 'User') {
            // Giả sử bạn đã đặt tên route cho user dashboard là 'user.dashboard'
            // Nếu chưa, bạn cần định nghĩa route này trong routes/web.php
            // và đảm bảo nó trỏ đến một controller action trả về view user_dashboard.blade.php
            // Ví dụ: Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
            // Hiện tại, nếu route 'user.dashboard' chưa tồn tại, dòng dưới sẽ gây lỗi.
            // Hãy đảm bảo route 'user.dashboard' đã được định nghĩa đúng.
            // Trong file web.php đã cung cấp ở bước trước, route này đang được comment.
            // Bạn cần bỏ comment và trỏ đến Controller xử lý view 'user.user_dashboard'
            // return redirect()->intended(route('user.dashboard')); // Sẽ sử dụng route này khi UserDashboardController sẵn sàng

            // Tạm thời, nếu route('user.dashboard') chưa sẵn sàng, có thể điều hướng đến một URL cố định
            // hoặc giữ nguyên điều hướng cũ và bạn tự điều chỉnh sau khi hoàn thiện UserDashboardController.
            // Ví dụ điều hướng đến URL cố định (BỎ COMMENT DÒNG DƯỚI NẾU MUỐN DÙNG):
             return redirect()->intended('/user/dashboard'); // Hoặc route(tên_route_user_dashboard_của_bạn)

            // Nếu route 'user.dashboard' chưa được cấu hình hoàn chỉnh, bạn có thể tạm thời vẫn điều hướng về HOME
            // và xử lý việc hiển thị nội dung khác nhau cho User trong view HOME đó.
            // Tuy nhiên, cách tốt nhất là có một route và controller riêng cho user dashboard.
        }

        // Đối với các vai trò khác (ví dụ: Admin), điều hướng đến RouteServiceProvider::HOME
        return redirect()->intended(RouteServiceProvider::HOME);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
