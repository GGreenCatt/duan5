<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Đăng bài') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Khu vực thống kê -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-600 p-4 rounded-lg text-white shadow">
                    <h3 class="text-lg font-semibold">Tổng số bài đăng</h3>
                    <p class="text-3xl mt-2">{{ $totalPosts }}</p>
                </div>
                <div class="bg-green-600 p-4 rounded-lg text-white shadow">
                    <h3 class="text-lg font-semibold">Tổng số lượt tương tác</h3>
                    <p class="text-3xl mt-2">{{ $totalViews }}</p>
                </div>
                <div class="bg-yellow-600 p-4 rounded-lg text-white shadow">
                    <h3 class="text-lg font-semibold">Bài đăng trong tuần</h3>
                    <p class="text-3xl mt-2">{{ $postsThisWeek }}</p>
                </div>
                <div class="bg-red-600 p-4 rounded-lg text-white shadow">
                    <h3 class="text-lg font-semibold">Bài đăng trong tháng</h3>
                    <p class="text-3xl mt-2">{{ $postsThisMonth }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
