@extends('layouts.guest_app')

@section('title', $post->title)

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
                            <span class="mx-2">&bull;</span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ $post->views }}
                            </span>
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

                        <div class="mt-8 pt-6 border-t dark:border-gray-600" id="comments-section">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Bình luận</h3>

                            <!-- Comment Form -->
                            <form id="comment-form" action="{{ route('comments.store') }}" method="POST" class="mb-8">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <div class="mb-4">
                                    <label for="content" class="sr-only">Nội dung bình luận</label>
                                    <textarea name="content" id="content" rows="4" class="w-full px-3 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Viết bình luận của bạn...">{{ old('content') }}</textarea>
                                    <p id="content-error" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Gửi bình luận</button>
                            </form>

                            <!-- Display Comments -->
                            <div id="comments-list" class="space-y-6">
                                @forelse ($post->comments->sortByDesc('created_at') as $comment)
                                    <div class="p-4 rounded-lg shadow-sm {{ ($comment->user && $comment->user->role == 'Admin') ? 'bg-blue-100 dark:bg-blue-800' : 'bg-gray-50 dark:bg-gray-700' }}">
                                        <div class="flex items-center mb-2">
                                            <div class="font-bold text-gray-800 dark:text-gray-100">
                                                @if ($comment->user)
                                                    {{ $comment->user->name }}
                                                    @if ($comment->user->role == 'Admin')
                                                        <span class="ml-2 px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Admin</span>
                                                    @endif
                                                @else
                                                    {{ $comment->anonymous_name }}
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 ml-3">{{ $comment->created_at->diffForHumans() }}</div>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                    </div>
                                @empty
                                    <p id="no-comments-message" class="text-gray-500 dark:text-gray-400">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                                @endforelse
                            </div>
                        </div>
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

    <script>
        $(document).ready(function() {
            $('#comment-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                console.log('Form submitted via AJAX.');

                let form = $(this);
                let url = form.attr('action');
                let method = form.attr('method');
                let formData = form.serialize();

                // Clear previous errors
                $('#content-error').text('');

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log('AJAX Success Response:', response);
                        if (response.success) {
                            // Append new comment to the list
                            let newCommentHtml = `
                                <div class="p-4 rounded-lg shadow-sm ${response.comment.author_role === 'Admin' ? 'bg-blue-100 dark:bg-blue-800' : 'bg-gray-50 dark:bg-gray-700'}">
                                    <div class="flex items-center mb-2">
                                        <div class="font-bold text-gray-800 dark:text-gray-100">
                                            ${response.comment.author}
                                            ${response.comment.author_role === 'Admin' ? '<span class="ml-2 px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Admin</span>' : ''}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 ml-3">${response.comment.created_at_for_humans}</div>
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300">${response.comment.content}</p>
                                </div>
                            `;
                            $('#comments-list').prepend(newCommentHtml); // Add to top
                            console.log('Comment HTML appended:', newCommentHtml);

                            // Clear the textarea
                            $('#content').val('');

                            // Remove "no comments" message if present
                            $('#no-comments-message').remove();

                            // Optional: Show a success notification
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error Response:', xhr);
                        if (xhr.status === 422) { // Validation error
                            let errors = xhr.responseJSON.errors;
                            if (errors.content) {
                                $('#content-error').text(errors.content[0]);
                            }
                        } else {
                            // Generic error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Đã xảy ra lỗi khi gửi bình luận.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection