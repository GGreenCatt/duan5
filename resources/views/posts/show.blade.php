<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi tiết bài đăng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg shadow-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100 space-y-8">

                    {{-- Tiêu đề bài đăng --}}
                    <div class="border-b pb-4">
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">Chủ đề: {{ $post->title }}</h1>
                        <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center space-x-4">
                            <span>Đăng bởi: <span class="font-medium">{{ $post->user->name ?? 'Ẩn danh' }}</span></span>
                            <span style="margin-left:15px ">Ngày đăng: <span class="font-medium">{{ $post->created_at->format('d/m/Y') }}</span></span>
                        </div>
                    </div>

                    {{-- Ảnh banner --}}
                    @if ($post->banner_image)
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $post->banner_image) }}" alt="Banner" class="w-2/3 max-w-lg h-auto object-contain rounded-lg shadow-md">
                        </div>
                    @endif

                    {{-- Nội dung bài đăng --}}
                    <div class="prose dark:prose-invert max-w-none border-b pb-4">
                        {!! $post->content !!}
                    </div>

                    {{-- Bộ sưu tập ảnh --}}
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Bộ sưu tập ảnh</h3>
                        @if (!empty($galleryImages) && count($galleryImages) > 0)
                            <div class="flex flex-wrap gap-4">
                                @foreach ($galleryImages as $image)
                                    <div class="relative">
                                        <a href="{{ asset('storage/' . $image) }}" data-lightbox="gallery" data-title="Hình ảnh trong thư viện">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" class="gallery-image w-auto h-32 max-h-32 object-contain rounded-lg shadow-md cursor-pointer">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-300">Không có ảnh trong bộ sưu tập.</p>
                        @endif
                    </div>

                    {{-- Hành động --}}
                    <div class="flex justify-end space-x-4 pt-4 border-t">
                        <a href="{{ route('posts.edit', $post) }}"
                        class="px-4 py-2 border border-blue-500 text-blue-500 rounded-md hover:bg-blue-500 hover:text-white transition-all">
                            ✏️ Chỉnh sửa
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 border border-red-500 text-red-500 rounded-md hover:bg-red-500 hover:text-white transition-all">
                                🗑️ Xóa
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Thêm thư viện Lightbox -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>

    <style>
        .gallery-image {
            height: 8rem;
            width: auto;
            max-height: 8rem;
            object-fit: contain;
        }
        .prose {
            font-size: 1.1rem;
            line-height: 1.7;
        }
    </style>
</x-app-layout>
