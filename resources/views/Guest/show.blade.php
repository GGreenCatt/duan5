@extends('layouts.guest_app')

@section('content')
    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        @if ($post->banner_image)
                            <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover rounded-lg mb-6">
                        @endif

                        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

                        <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm mb-6">
                            <span>Đăng bởi {{ $post->user->name }}</span>
                            <span class="mx-2">&bull;</span>
                            <span>{{ $post->created_at->format('d/m/Y') }}</span>
                             <span class="mx-2">&bull;</span>
                            <a href="{{ route('guest.posts.by_category', $post->category->slug) }}" class="hover:underline">{{ $post->category->name }}</a>
                        </div>
                        
                        <div class="prose dark:prose-invert max-w-none text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! $post->content !!}
                        </div>

                        {{-- ===== CẬP NHẬT: Thêm data-fancybox vào gallery ===== --}}
                        @if($post->gallery_images && count($post->gallery_images) > 0)
                            <div class="mt-8">
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Thư viện ảnh</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach ($post->gallery_images as $image)
                                        <a href="{{ asset('storage/' . $image) }}" data-fancybox="gallery" data-caption="{{ $post->title }}">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery image" class="rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        {{-- ====================================================== --}}
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200 border-b pb-2">Bài viết liên quan</h3>
                        <div class="space-y-4">
                             @forelse ($relatedPosts as $relatedPost)
                                <div class="flex items-start space-x-4">
                                    @if($relatedPost->banner_image)
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('posts.show', $relatedPost->slug) }}">
                                                <img src="{{ asset('storage/' . $relatedPost->banner_image) }}" alt="{{ $relatedPost->title }}" class="w-24 h-24 object-cover rounded-lg">
                                            </a>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('posts.show', $relatedPost->slug) }}" class="text-lg font-semibold text-gray-900 dark:text-white hover:text-blue-500 transition-colors duration-300">
                                            {{ $relatedPost->title }}
                                        </a>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $relatedPost->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                             @empty
                                 <p class="text-gray-500 dark:text-gray-400">Không có bài viết liên quan.</p>
                             @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection