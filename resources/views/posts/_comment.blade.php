{{--
    Recursive Comment Partial
    Accepts: $comment object
--}}
<div id="comment-{{ $comment->id }}" class="mb-4 p-4 rounded-lg shadow-sm bg-gray-100 dark:bg-gray-700">
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

    @if ($comment->status == 'pending')
        <p class="text-yellow-600 dark:text-yellow-400 text-sm mt-2">Đang đợi phê duyệt</p>
    @endif
    
    {{-- Display Like Count --}}
    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        <span>Likes: {{ $comment->interactions->count() }}</span>
    </div>

    {{-- Replies --}}
    @if($comment->replies->isNotEmpty())
        <div class="ml-8 mt-4 space-y-4 border-l-2 border-gray-200 dark:border-gray-600 pl-4">
            @foreach($comment->replies as $reply)
                @include('posts._comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>
