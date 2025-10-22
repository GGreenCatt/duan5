{{-- TẠO FILE MỚI NÀY: resources/views/guest/show.blade.php --}}
@extends('layouts.guest_app')

{{-- Đẩy CSS của Lightbox vào stack 'styles' của layout --}}
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <style>
        .prose img { max-width: 100%; height: auto; margin-left: auto; margin-right: auto; border-radius: 0.5rem; }
        .lightboxOverlay { background-color: rgba(0, 0, 0, 0.85) !important; }
        .lb-data .lb-caption { color: #ccc !important; }
    </style>
@endpush

@section('content')
    <div class="py-6 md:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100 space-y-6 md:space-y-8">

                    {{-- Breadcrumb --}}
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('guest.home') }}" class="inline-flex items-center text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                                    Trang chủ
                                </a>
                            </li>
                            @if($post->category)
                                <li>
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                        <a href="{{ route('guest.posts.by_category', $post->category->id) }}" class="ml-1 text-gray-700 hover:text-indigo-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{ $post->category->name }}</a>
                                    </div>
                                </li>
                             @endif
                        </ol>
                    </nav>

                    {{-- Tiêu đề và thông tin bài viết --}}
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $post->title }}</h1>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'U') }}&color=4A5568&background=E2E8F0&size=40" alt="{{ $post->user->name ?? 'User' }}" class="w-8 h-8 rounded-full mr-3">
                        <span>Đăng bởi <strong class="font-semibold">{{ $post->user->name ?? 'N/A' }}</strong></span>
                        <span class="mx-2">&bull;</span>
                        <span>{{ $post->created_at->format('d/m/Y') }}</span>
                    </div>

                    {{-- Ảnh banner --}}
                    @if($post->banner_image)
                        <div class="mb-6 rounded-lg overflow-hidden shadow-lg">
                            <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
                        </div>
                    @endif
                    
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300 border-l-4 border-indigo-500 pl-4 mb-6">{{ $post->short_description }}</p>

                    <div class="prose dark:prose-invert max-w-none text-lg leading-relaxed">{!! $post->content !!}</div>

                    @if($post->gallery_images && count($post->gallery_images) > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Thư viện ảnh</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($post->gallery_images as $image)
                                    <div><a href="{{ asset('storage/' . $image) }}" data-lightbox="gallery"><img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" class="rounded-lg shadow-md hover:opacity-80 transition-opacity duration-300"></a></div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($relatedPosts->isNotEmpty())
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Bài viết liên quan</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach($relatedPosts as $related)
                                    <a href="{{ route('posts.show', $related->id) }}" class="group block bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">{{ $related->title }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $related->created_at->format('d/m/Y') }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
    <script>
        lightbox.option({ 'resizeDuration': 200, 'wrapAround': true, 'fadeDuration': 300 });
    </script>
@endpush