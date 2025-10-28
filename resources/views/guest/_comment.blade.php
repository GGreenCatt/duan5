{{--
    Recursive Comment Partial
    Accepts: $comment object
--}}
<div id="comment-{{ $comment->id }}" class="p-4 rounded-lg shadow-sm {{ ($comment->user && $comment->user->role == 'Admin') ? 'bg-blue-100 dark:bg-blue-800' : 'bg-gray-50 dark:bg-gray-700' }}" data-comment-id="{{ $comment->id }}">
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
    <p class="text-gray-700 dark:text-gray-300 mb-2">{{ $comment->content }}</p>

    {{-- Comment Actions: Like and Reply --}}
    <div class="flex items-center space-x-4 text-sm">
        <button class="like-comment-btn flex items-center text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" data-comment-id="{{ $comment->id }}">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.085a2 2 0 00-1.736.97l-1.9 3.8z"></path></svg>
            <span>Thích</span>
            (<span id="comment-likes-count-{{ $comment->id }}">{{ $comment->interactions->count() }}</span>)
        </button>
        <button class="reply-btn flex items-center text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400" data-comment-id="{{ $comment->id }}">
             <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
            <span>Trả lời</span>
        </button>
    </div>

    {{-- Hidden Reply Form --}}
    <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 ml-8">
        <form class="comment-reply-form" action="{{ route('comments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <div class="mb-2">
                <label for="content-{{ $comment->id }}" class="sr-only">Nội dung trả lời</label>
                <textarea name="content" id="content-{{ $comment->id }}" rows="3" class="w-full px-3 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Viết câu trả lời của bạn..."></textarea>
                <p class="reply-content-error text-red-500 text-xs mt-1"></p>
            </div>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">Gửi trả lời</button>
        </form>
    </div>

    {{-- Replies Container --}}
    <div id="replies-container-{{ $comment->id }}" class="ml-8 mt-4 space-y-4 border-l-2 border-gray-200 dark:border-gray-600 pl-4">
        @if($comment->replies)
            @foreach($comment->replies->sortBy('created_at') as $reply)
                @include('guest._comment', ['comment' => $reply])
            @endforeach
        @endif
    </div>
</div>
