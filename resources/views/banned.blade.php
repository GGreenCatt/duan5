<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg text-center max-w-md w-full">
            <h1 class="text-4xl font-bold text-red-600 dark:text-red-400 mb-4">Tài khoản bị cấm</h1>
            <p class="text-gray-700 dark:text-gray-300 mb-6">
                Tài khoản của bạn đã bị cấm khỏi hệ thống. Vui lòng liên hệ quản trị viên để biết thêm chi tiết hoặc yêu cầu bỏ cấm.
            </p>
            <p class="text-gray-700 dark:text-gray-300 mb-6">
                Gửi email yêu cầu bỏ cấm đến: <a href="mailto:admin@example.com" class="text-blue-600 hover:underline dark:text-blue-400">admin@example.com</a>
            </p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Đăng xuất
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>