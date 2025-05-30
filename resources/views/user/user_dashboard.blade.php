@if (session('login_success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Đăng nhập thành công!',
                text: 'Chào mừng bạn quay trở lại.',
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
    @php session()->forget('login_success'); @endphp
@endif

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2 sm:mb-0">
                {{ __('Tin tức & Bài viết') }}
            </h2>
            @if(Auth::check() && Auth::user()->role === 'Admin')
                <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-green-500 dark:bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-400 dark:hover:bg-green-500 focus:outline-none focus:border-green-500 dark:focus:border-green-600 focus:ring focus:ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Thêm bài viết
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8" style="padding: 50px 250px 50px  250px">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 px-4"> {{-- Container rộng hơn một chút: max-w-3xl --}}
            <div class="flex justify-center mb-6">
                <div id="user-realtime-clock" class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md shadow-sm font-medium text-sm px-4 py-2">
                    {{-- Thời gian --}}
                </div>
            </div>

            @if(isset($posts) && $posts->count() > 0)
                <div class="space-y-6"> {{-- Khoảng cách giữa các card: space-y-6 --}}
                    @foreach($posts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="group block bg-white dark:bg-gray-800 shadow-lg hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] dark:hover:shadow-[0_0_15px_rgba(129,140,248,0.5)] rounded-xl overflow-hidden transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-start">
                            {{-- Cột nội dung bên trái --}}
                            <div class="w-full sm:flex-grow order-2 sm:order-1 sm:pr-5">
                                <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1.5">
                                    @if($post->category)
                                        @if($post->category->parent)
                                            {{ Str::limit($post->category->parent->name, 15) }} /
                                        @endif
                                        {{ Str::limit($post->category->name, 20) }}
                                    @else
                                        <span class="italic text-gray-500 dark:text-gray-400">Chưa phân loại</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-2 flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'U') }}&color=4A5568&background=E2E8F0&size=32" alt="{{ $post->user->name ?? 'User' }}" class="w-5 h-5 rounded-full mr-1.5">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ Str::limit($post->user->name ?? 'N/A', 20) }}</span>
                                    <span class="mx-1.5 text-gray-400 dark:text-gray-600">&bull;</span>
                                    <span class="text-gray-500 dark:text-gray-400">{{ optional($post->created_at)->diffForHumans() }}</span>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-white group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition-colors duration-200 mb-2 leading-tight text-center">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed line-clamp-3 py">
                                    {{ Str::limit(strip_tags($post->short_description), 150) }}
                                </p>
                            </div>

                            {{-- Cột ảnh banner bên phải --}}
                            <div class="w-full sm:w-1/3 md:w-4/12 lg:w-1/3 flex-shrink-0 order-1 sm:order-2 h-40 sm:h-auto sm:max-h-48 rounded-lg overflow-hidden mt-0 sm:mt-0 mb-3 sm:mb-0 shadow-md" style="padding: 20px 100px">
                                @if($post->banner_image)
                                    <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                @if ($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
                @endif

            @else
                 <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-10 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-5 text-xl font-semibold text-gray-700 dark:text-gray-300">Chưa có bài viết nào</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Hiện tại chưa có bài viết nào được đăng tải trên hệ thống.</p>
                    @if(Auth::check() && Auth::user()->role === 'Admin')
                    <div class="mt-6">
                        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-6 py-3 bg-green-500 dark:bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-600 dark:hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Tạo bài viết ngay
                        </a>
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<style>
    .line-clamp-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }
    .truncate-2-lines { /* Nếu bạn muốn giới hạn tiêu đề 2 dòng */
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateUserClock() {
            const now = new Date();
            const options = {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            };
            const clockElement = document.getElementById('user-realtime-clock');
            if(clockElement) {
                clockElement.textContent = now.toLocaleDateString('vi-VN', options).replace(',', ' -');
            }
        }
        updateUserClock();
        setInterval(updateUserClock, 1000);
    });
</script>
