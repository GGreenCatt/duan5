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
                        <span><strong>Tác giả:</strong> {{ $post->user ? $post->user->name : 'Anonymous' }}</span>
                        <span class="mx-3">|</span>
                        <span><strong>Ngày đăng:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</span>
                        <span class="mx-3">|</span>
                        <span><strong>Danh mục:</strong> {{ $post->category ? $post->category->name : 'Uncategorized' }}</span>
                        <span class="mx-3">|</span>
                        <span class="flex items-center ">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <strong>Lượt xem: </strong>  {{ $post->views }}
                        </span>
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
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Bình luận</h3>
                        @forelse ($post->comments as $comment)
                            <div id="comment-{{ $comment->id }}" class="mb-4 p-4 rounded-lg shadow-sm {{ ($comment->user && $comment->user->role == 'Admin') ? 'bg-blue-100 dark:bg-blue-800' : 'bg-gray-100 dark:bg-gray-700' }}">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $comment->user ? $comment->user->name : ($comment->anonymous_name ?? 'Anonymous') }}
                                        @if ($comment->user && $comment->user->role == 'Admin')
                                            <span class="ml-2 px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Admin</span>
                                        @endif
                                    </p>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-gray-800 dark:text-gray-200">{{ $comment->content }}</p>
                                @auth
                                    @if (Auth::user()->role == 'Admin')
                                        <form action="{{ route('posts.comments.destroy', $comment) }}" method="POST" class="delete-comment-form mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-400 text-sm">Xóa</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
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
                event.preventDefault(); // Prevent the default form submission

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
                        const formData = new FormData(form);
                        const method = form.querySelector('input[name="_method"]').value;

                        fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: data.success,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                const commentId = form.closest('.mb-4').id;
                                document.getElementById(commentId).remove();
                            } else {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'Đã xảy ra lỗi!',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Đã xảy ra lỗi!',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        });
                    }
                });
            });
        });
    });
</script>