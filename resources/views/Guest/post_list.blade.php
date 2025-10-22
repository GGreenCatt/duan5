@extends('layouts.guest_app')


@section('content')
    <div class="py-8 pt-8"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                {{-- Tiêu đề trang --}}
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 leading-tight text-center md:text-left">
                    @if(isset($categoryName))
                        {{ __('Danh mục: ') }} {{ $categoryName }}
                    @else
                        {{ __('Tất cả bài viết') }}
                    @endif
                </h1>
                {{-- Search Bar --}}
                <div class="relative w-full md:w-auto md:max-w-xs">
                    <input type="text" placeholder="Tìm kiếm bài viết..."
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:bg-gray-700 dark:text-gray-200 text-sm">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            {{-- =============== KẾT THÚC PHẦN HEADER MỚI =============== --}}


            {{-- Thanh Danh mục --}}
            @if(isset($categories) && $categories->count() > 0)
            <div class="mb-12 border-t border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center overflow-x-auto py-3 space-x-6">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex-shrink-0">Chủ đề:</span>
                    <a href="{{ route('guest.posts.index') }}" class="flex-shrink-0 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors
                       {{ !isset($categoryName) ? 'text-indigo-600 dark:text-indigo-400 font-bold' : '' }}">
                        Tất cả
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('guest.posts.by_category', $category->id) }}" class="flex-shrink-0 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors
                           {{ (isset($categoryName) && $categoryName == $category->name) ? 'text-indigo-600 dark:text-indigo-400 font-bold' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Lưới Bài viết --}}
            @if(isset($posts) && $posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($posts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="group block bg-white dark:bg-gray-800 shadow-lg hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] dark:hover:shadow-[0_0_15px_rgba(129,140,248,0.5)] rounded-xl overflow-hidden transition-all duration-300 ease-in-out transform hover:-translate-y-1 flex flex-col">
                        {{-- Ảnh --}}
                        <div class="h-48 w-full overflow-hidden">
                            @if($post->banner_image)
                                <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                            @else
                                <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        {{-- Nội dung --}}
                        <div class="p-4 sm:p-5 flex flex-col flex-grow">
                            <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1.5">
                                @if($post->category)
                                    @if($post->category->parent)
                                        {{ Str::limit($post->category->parent->name, 15) }} /
                                    @endif
                                    {{ Str::limit($post->category->name, 20) }}
                                @else
                                    <span class="italic text-gray-500 dark:text-gray-400">Chưa phân loại</span>
                                @endif
                                <span class="text-gray-500 dark:text-gray-400 mx-1">&bull;</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ optional($post->created_at)->format('M d, Y') }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition-colors duration-200 mb-2 leading-tight flex-grow">
                                {{ Str::limit($post->title, 60) }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed line-clamp-3 mb-4">
                                {{ Str::limit(strip_tags($post->short_description), 100) }}
                            </p>
                            <div class="mt-auto flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'U') }}&color=4A5568&background=E2E8F0&size=32" alt="{{ $post->user->name ?? 'User' }}" class="w-8 h-8 rounded-full mr-2" loading="lazy">
                                <div>
                                    <p class="font-medium text-gray-700 dark:text-gray-300 text-xs">{{ Str::limit($post->user->name ?? 'N/A', 20) }}</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">{{ optional($post->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                
                {{-- Phân trang --}}
                @if (isset($posts) && $posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->hasPages())
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
                @endif

            @else
                 {{-- Thông báo khi không có bài viết --}}
                 <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-10 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-5 text-xl font-semibold text-gray-700 dark:text-gray-300">Không tìm thấy bài viết</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Không có bài viết nào phù hợp.</p>
                </div>
            @endif
            
        </div>
    </div>
    @endsection 

<style>
    /* Helper class để giới hạn số dòng */
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
    .line-clamp-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }
    /* Đảm bảo ảnh không bị méo */
    img {
        object-fit: cover;
    }
    
    /* Ẩn thanh cuộn cho thanh danh mục */
    .overflow-x-auto::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
    .overflow-x-auto {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>