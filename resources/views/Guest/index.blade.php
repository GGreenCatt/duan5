@extends('layouts.guest_app') {{-- Kế thừa layout của khách --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">

            {{-- =============== PHẦN 1: HERO (TRENDING) =============== --}}
            @if(isset($trendingPosts) && $trendingPosts->count() >= 3)
                @php
                    $mainHeroPost = $trendingPosts->first();
                    $sideHeroPosts = $trendingPosts->slice(1, 2);
                @endphp
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-12">
                    
                    {{-- Bài viết chính (Bên trái) --}}
                    <div class="lg:col-span-2 group relative block bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                        {{-- CẬP NHẬT: Sửa $mainHeroPost thành $mainHeroPost->slug --}}
                        <a href="{{ route('posts.show', $mainHeroPost->slug) }}">
                            <div class="h-[450px] w-full">
                                @if($mainHeroPost->banner_image)
                                    <img src="{{ asset('storage/' . $mainHeroPost->banner_image) }}" alt="{{ $mainHeroPost->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-xl">
                                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute bottom-0 left-0 w-full p-6 bg-gradient-to-t from-black/80 to-transparent">
                                <div class="text-xs font-semibold text-white/90 uppercase tracking-wider mb-2">
                                    @if($mainHeroPost->category)
                                        <span class="bg-indigo-600 py-1 px-2.5 rounded">{{ $mainHeroPost->category->name }}</span>
                                    @endif
                                    <span class="ml-2">{{ optional($mainHeroPost->created_at)->format('F j, Y') }}</span>
                                </div>
                                <h3 class="text-2xl lg:text-3xl font-bold text-white transition-colors duration-200 mb-2 leading-tight">
                                    {{ limitText($mainHeroPost->title, 60, '...') }}
                                </h3>
                                <div class="flex items-center text-sm text-gray-200">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($mainHeroPost->user->name ?? 'U') }}&color=EBF4FF&background=7F9CF5&size=40" alt="{{ $mainHeroPost->user->name ?? 'User' }}" class="w-6 h-6 rounded-full mr-2 border-2 border-white/50" loading="lazy">
                                    <span>{{ $mainHeroPost->user->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- 2 Bài viết phụ (Bên phải) --}}
                    <div class="lg:col-span-1 space-y-6">
                        @foreach($sideHeroPosts as $post)
                        <div class="group relative block bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden h-[213px]">
                            {{-- CẬP NHẬT: Sửa $post thành $post->slug --}}
                            <a href="{{ route('posts.show', $post->slug) }}">
                                @if($post->banner_image)
                                    <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-xl">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/80 to-transparent">
                                    <div class="text-xs font-semibold text-white/90 uppercase tracking-wider mb-1">
                                        @if($post->category)
                                            <span class="bg-indigo-600 py-0.5 px-2 rounded">{{ $post->category->name }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-base font-bold text-white transition-colors duration-200 leading-tight line-clamp-2">
                                        {{ $post->title }}
                                    </h3>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- =============== KẾT THÚC PHẦN 1: HERO (TRENDING) =============== --}}


            {{-- =============== BẮT ĐẦU PHẦN 2: THANH DANH MỤC =============== --}}
           @if(isset($categories) && $categories->count() > 0)
            <div class="mb-12 border-t border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center overflow-x-auto py-3 space-x-6">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex-shrink-0">Chủ đề:</span>
                    @foreach($categories as $category)
                        {{-- SỬA Ở ĐÂY: $category->id thành $category->slug --}}
                        <a href="{{ route('guest.posts.by_category', $category->slug) }}" class="flex-shrink-0 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            {{-- =============== KẾT THÚC PHẦN 2: THANH DANH MỤC =============== --}}


            {{-- =============== BẮT ĐẦU PHẦN 3: LƯỚI BÀI VIẾT CHÍNH =============== --}}
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6 text-center sm:text-left">Bài viết mới nhất</h2>
            
            @if(isset($posts) && $posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($posts as $post)
                    {{-- CẬP NHẬT: Sửa $post thành $post->slug --}}
                    <a href="{{ route('posts.show', $post->slug) }}" class="group block bg-white dark:bg-gray-800 shadow-lg hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] dark:hover:shadow-[0_0_15px_rgba(129,140,248,0.5)] rounded-xl overflow-hidden transition-all duration-300 ease-in-out transform hover:-translate-y-1 flex flex-col">
                        <div class="h-48 w-full overflow-hidden">
                            @if($post->banner_image)
                                <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                            @else
                                <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 sm:p-5 flex flex-col flex-grow">
                            <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1.5">
                                @if($post->category)
                                    @if($post->category->parent)
                                        {{ Str::limit($post->category->parent->name, 15, '...') }} /
                                    @endif
                                    {{ Str::limit($post->category->name, 20, '...') }}
                                @else
                                    <span class="italic text-gray-500 dark:text-gray-400">Chưa phân loại</span>
                                @endif
                                <span class="text-gray-500 dark:text-gray-400 mx-1">&bull;</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ optional($post->created_at)->format('M d, Y') }}</span>
                            </div>
                                                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition-colors duration-200 mb-2 leading-tight flex-grow">
                                                            {{ limitText($post->title, 60, '...') }}
                                                        </h3>                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed line-clamp-3 mb-4">
                                {{ limitText(strip_tags($post->short_description), 100, '...') }}
                            </p>
                            <div class="mt-auto flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'U') }}&color=4A5568&background=E2E8F0&size=32" alt="{{ $post->user->name ?? 'User' }}" class="w-8 h-8 rounded-full mr-2" loading="lazy">
                                <div>
                                    <p class="font-medium text-gray-700 dark:text-gray-300 text-xs">{{ limitText($post->user->name ?? 'N/A', 20, '...') }}</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">{{ optional($post->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                
                @if (isset($posts) && $posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->hasPages())
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
                @endif

            @elseif(!isset($trendingPosts) || $trendingPosts->count() == 0)
                 <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-10 text-center col-span-1 md:col-span-2 lg:col-span-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <h3 class="mt-5 text-xl font-semibold text-gray-700 dark:text-gray-300">Chưa có bài viết nào</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Hiện tại chưa có bài viết nào được đăng tải trên hệ thống.</p>
                </div>
            @endif
            {{-- =============== KẾT THÚC PHẦN 3: LƯỚI BÀI VIẾT CHÍNH =============== --}}


            {{-- =============== BẮT ĐẦU PHẦN 4: CÔNG NGHỆ & NGÂN HÀNG =============== --}}
            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700 grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Công nghệ</h3>
                        <a href="{{ route('guest.posts.by_category', ['category' => 'cong-nghe']) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">Xem thêm &rarr;</a>
                    </div>
                    @if(isset($congNghePosts) && $congNghePosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($congNghePosts as $post)
                            {{-- CẬP NHẬT: Sửa $post thành $post->slug --}}
                            <a href="{{ route('posts.show', $post->slug) }}" class="group flex items-center space-x-3">
                                <div class="w-16 h-16 flex-shrink-0 overflow-hidden rounded-md">
                                    @if($post->banner_image)
                                        <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                    @else
                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-md"><svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-base font-semibold text-gray-900 dark:text-gray-100 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition-colors duration-200 line-clamp-2 leading-tight">{{ limitText($post->title, 60, '...') }}</h5>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'U') }}&color=718096&background=E2E8F0&size=20&font-size=0.4" alt="{{ $post->user->name ?? 'User' }}" class="w-4 h-4 rounded-full mr-1.5" loading="lazy">
                                        <span>{{ limitText($post->user->name ?? 'N/A', 15, '...') }}</span><span class="mx-1.5">&bull;</span><span>{{ optional($post->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">Không có bài viết nào trong mục "Công nghệ".</p>
                    @endif
                </div>

                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Ngân Hàng</h3>
                        <a href="{{ route('guest.posts.by_category', ['category' => 'ngan-hang']) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">Xem thêm &rarr;</a>
                    </div>
                    @if(isset($nganHangPosts) && $nganHangPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($nganHangPosts as $post)
                            {{-- CẬP NHẬT: Sửa $post thành $post->slug --}}
                            <a href="{{ route('posts.show', $post->slug) }}" class="group flex items-center space-x-3">
                                <div class="w-16 h-16 flex-shrink-0 overflow-hidden rounded-md">
                                    @if($post->banner_image)
                                        <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                    @else
                                         <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-md"><svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-base font-semibold text-gray-900 dark:text-gray-100 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition-colors duration-200 line-clamp-2 leading-tight">{{ limitText($post->title, 60, '...') }}</h5>
                                     <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'U') }}&color=718096&background=E2E8F0&size=20&font-size=0.4" alt="{{ $post->user->name ?? 'User' }}" class="w-4 h-4 rounded-full mr-1.5" loading="lazy">
                                        <span>{{ limitText($post->user->name ?? 'N/A', 15, '...') }}</span><span class="mx-1.5">&bull;</span><span>{{ optional($post->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">Không có bài viết nào trong mục "Ngân Hàng".</p>
                    @endif
                </div>
            </div>
            {{-- =============== KẾT THÚC PHẦN 4: CÔNG NGHỆ & NGÂN HÀNG =============== --}}
            
        </div>
    </div>
@endsection

@push('styles')
<style>
    .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
    .line-clamp-3 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3; }
    .line-clamp-4 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 4; }
    img { object-fit: cover; }
    .overflow-x-auto::-webkit-scrollbar { display: none; }
    .overflow-x-auto { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush