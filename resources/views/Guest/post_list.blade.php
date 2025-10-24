@extends('layouts.guest_app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">

        {{-- Breadcrumbs --}}
        <div class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('guest.home') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Trang chủ</a>
            <span class="mx-2">&raquo;</span>
            @if(isset($category))
                @if($category->parent)
                    <a href="{{ route('guest.posts.by_category', $category->parent->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $category->parent->name }}</a>
                    <span class="mx-2">&raquo;</span>
                @endif
                <span class="text-gray-700 dark:text-gray-200">{{ $category->name }}</span>
            @else
                <span class="text-gray-700 dark:text-gray-200">Tất cả bài viết</span>
            @endif
        </div>

        <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100 mb-8 text-center">
            @if(isset($category))
                {{ $category->name }}
            @else
                Khám phá bài viết
            @endif
        </h1>

        @if(isset($posts) && $posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($posts as $post)
                {{-- SỬA LỖI: Thêm ->slug vào đây --}}
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
                                {{ Str::limit($post->category->name, 20) }}
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
            
            @if ($posts->hasPages())
            <div class="mt-10">
                {{ $posts->links() }}
            </div>
            @endif

        @else
             <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-10 text-center col-span-1 md:col-span-2 lg:col-span-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <h3 class="mt-5 text-xl font-semibold text-gray-700 dark:text-gray-300">Không tìm thấy bài viết</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Rất tiếc, không có bài viết nào trong danh mục này.</p>
            </div>
        @endif
        
    </div>
</div>
@endsection