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

                    <div class="flex flex-wrap items-center text-gray-500 dark:text-gray-400 text-sm mb-6 border-y dark:border-gray-600 py-3">
                        <span><strong>Tác giả:</strong> {{ $post->user ? $post->user->name : 'Anonymous' }}</span>
                        <span class="mx-3">|</span>
                        <span><strong>Ngày đăng:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</span>
                        <span class="mx-3">|</span>
                        <span><strong>Danh mục:</strong> {{ $post->category ? $post->category->name : 'Uncategorized' }}</span>
                        <span class="mx-3">|</span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <strong>Lượt xem: </strong>&nbsp;{{ $post->views }}
                        </span>
                    </div>

                    {{-- Interaction Stats --}}
                    <div class="flex flex-wrap items-center text-gray-500 dark:text-gray-400 text-sm mb-6 py-3">
                        <span class="flex items-center mr-4">
                            <svg class="w-5 h-5 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.562 8H12V4a2 2 0 00-2-2v1.293a1 1 0 01-1.707 0V2a2 2 0 00-2 2v4.333l.002.001a1 1 0 01.706.998V10.5z"></path></svg>
                            <strong>Likes:</strong>&nbsp;{{ $post->likes->count() }}
                        </span>
                        <span class="flex items-center">
                             <svg class="w-5 h-5 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.106-1.79l-.05-.025A4 4 0 0011.057 2H5.641a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.438 12H8v4a2 2 0 002 2v-1.293a1 1 0 011.707 0V18a2 2 0 002-2v-4.333l-.002-.001a1 1 0 01-.706-.998V9.5z"></path></svg>
                            <strong>Dislikes:</strong>&nbsp;{{ $post->dislikes->count() }}
                        </span>
                    </div>
                    
                    <div class="prose dark:prose-invert max-w-none text-lg text-gray-300 leading-relaxed">
                        {!! $post->content !!}
                    </div>

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

                    <div class="mt-8 pt-6 border-t dark:border-gray-600">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Bình luận</h3>
                        @forelse ($post->comments as $comment)
                            @include('posts._admin_comment', ['comment' => $comment])
                        @empty
                            <p class="text-gray-600 dark:text-gray-400">Chưa có bình luận nào.</p>
                        @endforelse
                    </div>

                    <div class="mt-8 pt-6 border-t dark:border-gray-600">
                         <a href="{{ route('posts.list') }}" class="text-indigo-400 hover:text-indigo-300">&larr; Quay lại danh sách</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-comment-form').forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Bạn sẽ không thể hoàn tác hành động này!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Có, xóa nó!',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const url = form.action;
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        fetch(url, {
                            method: 'POST', // Forms use POST, but we specify DELETE via _method field
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            },
                            body: new FormData(form) // Send form data to include _method
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Đã xóa!', data.success, 'success');
                                form.closest('#comment-' + form.closest('[data-comment-id]').dataset.commentId).remove();
                            } else {
                                Swal.fire('Lỗi!', 'Đã xảy ra lỗi khi xóa bình luận.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Lỗi!', 'Đã xảy ra lỗi khi xóa bình luận.', 'error');
                        });
                    }
                });
            });
        });
    });
</script>