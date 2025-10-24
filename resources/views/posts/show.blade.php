<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Chi tiết bài đăng
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if ($post->banner_image)
                        <img src="{{ asset('storage/' . $post->banner_image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover rounded-lg mb-6 max-h-96">
                    @endif

                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

                    <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm mb-6 border-y dark:border-gray-600 py-3">
                        <span><strong>Tác giả:</strong> {{ $post->user->name }}</span>
                        <span class="mx-3">|</span>
                        <span><strong>Ngày đăng:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</span>
                         <span class="mx-3">|</span>
                        <span><strong>Danh mục:</strong> {{ $post->category->name }}</span>
                    </div>
                    
                    <div class="prose dark:prose-invert max-w-none text-lg text-gray-300 leading-relaxed">
                        {!! $post->content !!}
                    </div>

                    {{-- ===== CẬP NHẬT: Thêm data-fancybox vào gallery ===== --}}
                    @if($post->gallery_images && count($post->gallery_images) > 0)
                        <div class="mt-8 pt-6 border-t dark:border-gray-600">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Thư viện ảnh</h3>
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
                                @foreach ($post->gallery_images as $image)
                                    <a href="{{ asset('storage/' . $image) }}" data-fancybox="admin-gallery" data-caption="{{ $post->title }}">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery image" class="rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 aspect-square object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- ====================================================== --}}

                    <div class="mt-8 pt-6 border-t dark:border-gray-600">
                         <a href="{{ route('posts.list') }}" class="text-indigo-400 hover:text-indigo-300">&larr; Quay lại danh sách</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>