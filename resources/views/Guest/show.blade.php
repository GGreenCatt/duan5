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

                        <h1 class="text-4xl font-extold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

                        <div class="flex flex-wrap items-center text-gray-500 dark:text-gray-400 text-sm mb-6">
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

                        {{-- Post Interactions --}}
                        <div class="mt-8 pt-6 border-t dark:border-gray-600 flex items-center space-x-6">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Bạn thấy bài viết này thế nào?</h3>
                            <div class="flex items-center space-x-4">
                                <button id="like-post-btn" class="post-interact-btn flex items-center text-gray-600 dark:text-gray-400 transition-colors duration-200 @auth hover:text-blue-600 dark:hover:text-blue-400 @endauth @guest cursor-not-allowed opacity-50 @endguest" data-post-id="{{ $post->id }}" data-type="like" @guest disabled @endguest>
                                    <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.085a2 2 0 00-1.736.97l-1.9 3.8z"></path></svg>
                                    <span>Thích</span>
                                    (<span id="post-likes-count">{{ $post->likes()->count() }}</span>)
                                </button>
                                <button id="dislike-post-btn" class="post-interact-btn flex items-center text-gray-600 dark:text-gray-400 transition-colors duration-200 @auth hover:text-red-600 dark:hover:text-red-400 @endauth @guest cursor-not-allowed opacity-50 @endguest" data-post-id="{{ $post->id }}" data-type="dislike" @guest disabled @endguest>
                                    <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.738 3h4.017c.163 0 .326.02.485.06L17 4m-7 10v5a2 2 0 002 2h.085a2 2 0 001.736-.97l1.9-3.8z"></path></svg>
                                    <span>Không thích</span>
                                    (<span id="post-dislikes-count">{{ $post->dislikes()->count() }}</span>)
                                </button>
                            </div>
                        </div>

                        {{-- Comments Section --}}
                        <div class="mt-8 pt-6 border-t dark:border-gray-600" id="comments-section">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Bình luận (<span id="comments-count">{{ $post->comments->count() }}</span>)</h3>

                            @auth
                            <form id="comment-form" action="{{ route('comments.store') }}" method="POST" class="mb-8">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <div class="mb-4">
                                    <label for="content" class="sr-only">Nội dung bình luận</label>
                                    <textarea name="content" id="content" rows="4" class="w-full px-3 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Viết bình luận của bạn..."></textarea>
                                    <p id="content-error" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Gửi bình luận</button>
                            </form>
                            @endauth
                            @guest
                            <div class="mb-8 p-4 border rounded-lg text-center bg-gray-50 dark:bg-gray-700">
                                <p class="text-gray-600 dark:text-gray-300">Vui lòng <a href="{{ route('login') }}" class="text-blue-500 hover:underline font-semibold">đăng nhập</a> để bình luận và tương tác với bài viết.</p>
                            </div>
                            @endguest

                            <div id="comments-list" class="space-y-6">
                                @forelse ($post->comments as $comment)
                                    @include('guest._comment', ['comment' => $comment, 'session_anonymous_name' => session('anonymous_name')])
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

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const mainContentArea = document.querySelector('.md\\:col-span-2');

    // Pass the authenticated user ID to JavaScript
    const authenticatedUserId = {{ Auth::check() ? Auth::id() : 'null' }};
    const sessionAnonymousName = '{{ session('anonymous_name', '') }}'; // Ensure it's always a string

    // Function to handle all AJAX requests
    async function sendRequest(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });
            if (!response.ok) {
                const errorData = await response.json();
                // If the user is not logged in, redirect them
                if (response.status === 401 || response.status === 403) {
                     Swal.fire({
                        icon: 'warning',
                        title: 'Yêu cầu đăng nhập',
                        text: 'Vui lòng đăng nhập để thực hiện hành động này.',
                        confirmButtonText: 'Đăng nhập',
                        showCancelButton: true,
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('login') }}';
                        }
                    });
                }
                throw { status: response.status, data: errorData };
            }
            return await response.json();
        } catch (error) {
            console.error('AJAX Error:', error);
            if (error.status !== 401 && error.status !== 403) {
                 Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Đã có lỗi xảy ra. Vui lòng thử lại!',
                });
            }
            throw error;
        }
    }

    @auth
    // --- Main Comment Form Submission ---
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const contentTextarea = form.querySelector('textarea[name="content"]');
            const content = contentTextarea.value.trim();
            const errorP = form.querySelector('#content-error');
            
            if (!content) {
                if (errorP) errorP.textContent = 'Vui lòng nhập nội dung.';
                return;
            } else {
                if (errorP) errorP.textContent = '';
            }

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            sendRequest(form.action, data)
                .then(response => {
                    if (response.success) {
                        const newCommentHtml = createCommentHtml(response.comment, {{ $post->id }});

                        const commentsList = document.getElementById('comments-list');
                        commentsList.insertAdjacentHTML('afterbegin', newCommentHtml);
                        document.getElementById('no-comments-message')?.remove();
                        
                        const countSpan = document.getElementById('comments-count');
                        countSpan.textContent = parseInt(countSpan.textContent) + 1;

                        form.reset();
                    }
                })
                .catch(error => {
                    if (error.status === 422 && error.data.errors) {
                        if (error.data.errors.content && errorP) {
                            errorP.textContent = error.data.errors.content[0];
                        }
                    }
                });
        });
    }
    @endauth

    // --- Event Delegation for interactions and reply form submissions ---
    mainContentArea.addEventListener('click', function(e) {
        // Post Interactions
        const postInteractBtn = e.target.closest('.post-interact-btn');
        if (postInteractBtn) {
            e.preventDefault();
            if (authenticatedUserId === null) {
                // This is redundant due to the disabled attribute, but good for security
                window.location.href = '{{ route('login') }}';
                return;
            }
            const postId = postInteractBtn.dataset.postId;
            const type = postInteractBtn.dataset.type;

            sendRequest('{{ route("posts.interact") }}', { post_id: postId, type: type })
                .then(data => {
                    document.getElementById('post-likes-count').textContent = data.likes;
                    document.getElementById('post-dislikes-count').textContent = data.dislikes;
                }).catch(() => {}); // Catch block to prevent unhandled promise rejection console errors
            return; 
        }

        // Comment Like button
        const likeBtn = e.target.closest('.like-comment-btn');
        if (likeBtn) {
            e.preventDefault();
            if (authenticatedUserId === null) {
                window.location.href = '{{ route('login') }}';
                return;
            }
            const commentId = likeBtn.dataset.commentId;
            sendRequest('{{ route("comments.interact") }}', { comment_id: commentId })
                .then(data => {
                    document.getElementById(`comment-likes-count-${commentId}`).textContent = data.likes;
                }).catch(() => {});
            return;
        }

        @auth
        // Reply button
        const replyBtn = e.target.closest('.reply-btn');
        if (replyBtn) {
            const commentId = replyBtn.dataset.commentId;
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm) {
                replyForm.classList.toggle('hidden');
            }
        }
        @endauth
    });

    @auth
    // Delegate reply form submissions
    mainContentArea.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.matches('.comment-reply-form')) {
            e.preventDefault();
            
            const contentTextarea = form.querySelector('textarea[name="content"]');
            const content = contentTextarea.value.trim();
            const errorP = form.querySelector('.reply-content-error');
            
            if (!content) {
                if (errorP) errorP.textContent = 'Vui lòng nhập nội dung.';
                return;
            } else {
                if (errorP) errorP.textContent = '';
            }

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            sendRequest(form.action, data)
                .then(response => {
                    if (response.success) {
                        const newCommentHtml = createCommentHtml(response.comment, {{ $post->id }});

                        const repliesContainer = document.getElementById(`replies-container-${response.comment.parent_id}`);
                        repliesContainer.insertAdjacentHTML('beforeend', newCommentHtml);
                        
                        const countSpan = document.getElementById('comments-count');
                        countSpan.textContent = parseInt(countSpan.textContent) + 1;

                        form.reset();
                        form.parentElement.classList.add('hidden');
                    }
                })
                .catch(error => {
                    if (error.status === 422 && error.data.errors) {
                        if (error.data.errors.content && errorP) {
                            errorP.textContent = error.data.errors.content[0];
                        }
                    }
                });
        }
    });
    @endauth

    // Helper function to create comment HTML
    function createCommentHtml(comment, postId) {
        const isAdmin = comment.author_role === 'Admin';
        const authorHtml = isAdmin
            ? `${comment.author} <span class="ml-2 px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Admin</span>`
            : comment.author;

        let isCommentAuthor = false;
        if (comment.user_id !== null) {
            isCommentAuthor = comment.user_id === authenticatedUserId;
        } else {
            // This case should no longer happen for new comments, but good for old ones
            isCommentAuthor = comment.anonymous_name === sessionAnonymousName;
        }

        const pendingBadge = comment.status === 'pending' && isCommentAuthor
            ? '<span class="ml-3 px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-800 dark:text-yellow-100">Đang đợi phê duyệt</span>'
            : comment.status === 'rejected' && isCommentAuthor
                ? '<span class="ml-3 px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-100">Bị từ chối</span>'
                : '';
        
        let interactionButtons = '';
        if (authenticatedUserId !== null) {
            interactionButtons = `
                <button class="like-comment-btn flex items-center text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" data-comment-id="${comment.id}">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.085a2 2 0 00-1.736.97l-1.9 3.8z"></path></svg>
                    <span>Thích</span> (<span id="comment-likes-count-${comment.id}">${comment.likes_count || 0}</span>)
                </button>
                <button class="reply-btn flex items-center text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400" data-comment-id="${comment.id}">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                    <span>Trả lời</span>
                </button>
            `;
        } else {
             interactionButtons = `
                <div class="flex items-center text-gray-500 dark:text-gray-400 opacity-50">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.085a2 2 0 00-1.736.97l-1.9 3.8z"></path></svg>
                    <span>Thích</span> (<span id="comment-likes-count-${comment.id}">${comment.likes_count || 0}</span>)
                </div>
             `;
        }

        let replyForm = '';
        if (authenticatedUserId !== null) {
            replyForm = `
            <div id="reply-form-${comment.id}" class="hidden mt-4 ml-8">
                <form class="comment-reply-form" action="{{ route('comments.store') }}" method="POST">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="post_id" value="${postId}">
                    <input type="hidden" name="parent_id" value="${comment.id}">
                    <div class="mb-2">
                        <textarea name="content" rows="3" class="w-full px-3 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Viết câu trả lời của bạn..."></textarea>
                        <p class="reply-content-error text-red-500 text-xs mt-1"></p>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">Gửi trả lời</button>
                </form>
            </div>
            `;
        }

        return `
        <div id="comment-${comment.id}" class="p-4 rounded-lg shadow-sm ${isAdmin ? 'bg-blue-100 dark:bg-blue-800' : 'bg-gray-50 dark:bg-gray-700'}" data-comment-id="${comment.id}">
            <div class="flex items-center mb-2">
                <div class="font-bold text-gray-800 dark:text-gray-100">${authorHtml}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 ml-3">${comment.created_at_for_humans}</div>
                ${pendingBadge}
            </div>
            <p class="text-gray-700 dark:text-gray-300 mb-2">${comment.content}</p>
            <div class="flex items-center space-x-4 text-sm">
                ${interactionButtons}
            </div>
            ${replyForm}
            <div id="replies-container-${comment.id}" class="ml-8 mt-4 space-y-4 border-l-2 border-gray-200 dark:border-gray-600 pl-4"></div>
        </div>
        `;
    }
});
</script>
@endpush