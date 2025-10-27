@extends('layouts.guest_app')
@section('title', 'Danh sách danh mục')
@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
        {{-- Header của trang --}}
        <header class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
                Khám Phá Các Chủ Đề
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Tìm kiếm và khám phá hàng trăm bài viết được sắp xếp gọn gàng theo từng danh mục mà bạn quan tâm.
            </p>
        </header>

        {{-- Kiểm tra nếu không có danh mục nào --}}
        @if($parentCategories->isEmpty())
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Không có danh mục</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hiện tại chưa có danh mục nào được tạo.</p>
            </div>
        @else
            {{-- Vòng lặp các danh mục cha --}}
            <div class="space-y-16">
                @foreach($parentCategories as $parent)
                    <section>
                        {{-- Tiêu đề danh mục cha --}}
                        <div class="flex items-center mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                            @if($parent->image)
                                <img src="{{ asset('storage/' . $parent->image) }}" alt="{{ $parent->name }}" class="w-10 h-10 rounded-full object-cover mr-4 shadow-sm">
                            @endif
                            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200">
                                {{-- Danh mục cha cũng có thể click được --}}
                                <a href="{{ route('guest.posts.by_category', $parent->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-300">
                                    {{ $parent->name }}
                                </a>
                            </h2>
                        </div>
                        
                        {{-- Lưới danh mục con --}}
                        @if($parent->children->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($parent->children as $child)
                                    <div class="transform transition-transform duration-300 hover:-translate-y-1">
                                        <a href="{{ route('guest.posts.by_category', $child->slug) }}" class="block h-full p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl hover:border-indigo-500/50 border border-transparent dark:border-gray-700 dark:hover:border-indigo-500/50">
                                            <h3 class="font-semibold text-xl text-gray-900 dark:text-white mb-2">{{ $child->name }}</h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-4">{{ $child->description ?: 'Chưa có mô tả cho danh mục này.' }}</p>
                                            <div class="mt-auto text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                                Xem ({{ $child->posts_count }}) bài viết &rarr;
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <p class="text-gray-500 dark:text-gray-400 italic">Danh mục này chưa có chủ đề con.</p>
                            </div>
                        @endif
                    </section>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection