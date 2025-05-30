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
                {{ __('Danh sách bài viết') }}
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

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="flex justify-center mb-5">
                <div id="user-realtime-clock" class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md shadow font-semibold text-sm px-3 py-1.5">
                    {{-- Thời gian --}}
                </div>
            </div>

            @if(isset($posts) && $posts->count() > 0)
                <div class="space-y-4"> {{-- Giữ nguyên space-y-4 hoặc điều chỉnh nếu cần --}}
                    @foreach($posts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="block bg-white dark:bg-gray-800 shadow-md hover:shadow-lg rounded-lg overflow-hidden transition-shadow duration-300">
                        <div class="p-3 flex flex-col sm:flex-row"> {{-- Padding card p-3 --}}
                            {{-- Cột nội dung bên trái --}}
                            <div class="w-full sm:flex-grow order-2 sm:order-1 sm:pr-3"> {{-- sm:flex-grow để chiếm không gian còn lại, sm:pr-3 cho khoảng cách --}}
                                {{-- Dòng 1: Danh mục cha-con --}}
                                <div class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide mb-1 text-left">
                                    @if($post->category)
                                        @if($post->category->parent)
                                            {{ Str::limit($post->category->parent->name, 15) }} /
                                        @endif
                                        {{ Str::limit($post->category->name, 20) }}
                                    @else
                                        <span class="italic text-gray-400">Không danh mục</span>
                                    @endif
                                </div>

                                {{-- Dòng 2: Tên người đăng --}}
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-2 text-left">
                                    Đăng bởi:
                                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ Str::limit($post->user->name ?? 'N/A', 20) }}</span>
                                    <span class="mx-1">&bull;</span>
                                    <span>{{ optional($post->created_at)->translatedFormat('d/m/Y') }}</span>
                                </div>

                                {{-- Dòng 3: Tên chủ đề (Tiêu đề) - ở giữa --}}
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 leading-tight text-center">
                                    {{ $post->title }}
                                </h3>

                                {{-- Dòng 4: Mô tả ngắn --}}
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed line-clamp-2 text-left">
                                    {{ Str::limit(strip_tags($post->short_description), 120) }}
                                </p>
                            </div>

                            {{-- Cột ảnh banner bên phải (kích thước bạn đã điều chỉnh trong code gửi lên) --}}
                            <div class="w-full sm:w-1/6 order-1 sm:order-2 h-28 sm:h-28 flex-shrink-0 rounded-md overflow-hidden mb-3 sm:mb-0">
                                @if($post->banner_image)
                                    <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                @if ($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
                @endif

            @else
                 <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Chưa có bài viết nào</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm">Hiện tại chưa có bài viết nào được đăng tải.</p>
                    @if(Auth::check() && Auth::user()->role === 'Admin')
                    <div class="mt-4">
                        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-green-500 dark:bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-400 dark:hover:bg-green-500 focus:outline-none focus:border-green-500 dark:focus:border-green-600 focus:ring focus:ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
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
    .line-clamp-2 { /* Giữ 2 dòng cho mô tả */
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
