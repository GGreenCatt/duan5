<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Quản lý Bình luận
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bài viết</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tác giả</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nội dung</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trạng thái</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ngày tạo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($comments as $comment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $comment->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('posts.show', $comment->post->slug) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">{{ $comment->post->title }}</a></td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $comment->user ? $comment->user->name : ($comment->anonymous_name ?? 'Anonymous') }}</td>
                                        <td class="px-6 py-4">{{ Str::limit($comment->content, 50) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white {{ $comment->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100' }} dark:{{ $comment->status == 'approved' ? 'bg-green-800 text-green-100' : 'bg-yellow-800 text-yellow-100' }}">
                                                {{ $comment->status == 'approved' ? 'Đã phê duyệt' : 'Đang chờ' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                @if ($comment->status == 'pending')
                                                    <button type="button" class="approve-comment-btn inline-flex items-center justify-center p-2 rounded-md text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200" data-comment-id="{{ $comment->id }}" data-approve-url="{{ route('admin.comments.approve', $comment) }}" title="Phê duyệt">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                @endif
                                                <button type="button" class="delete-comment-btn inline-flex items-center justify-center p-2 rounded-md text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200" data-comment-id="{{ $comment->id }}" data-delete-url="{{ route('admin.comments.destroy', $comment) }}" title="Xóa">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">Không có bình luận nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function sendAjaxRequest(url, method, data = {}) {
            console.log(`Sending AJAX request to: ${url} with method: ${method}`);
            const headers = {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            };

            if (method === 'PUT' || method === 'DELETE') {
                headers['X-HTTP-Method-Override'] = method;
            }

            try {
                const response = await fetch(url, {
                    method: 'POST', // Always send as POST for method override
                    headers: headers,
                    body: method === 'GET' ? null : JSON.stringify(data),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Đã xảy ra lỗi!');
                }

                return await response.json();
            } catch (error) {
                console.error('AJAX Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message || 'Đã có lỗi xảy ra. Vui lòng thử lại!',
                });
                throw error; // Re-throw to allow further handling if needed
            }
        }

        // Handle Approve Button Click
        document.querySelectorAll('.approve-comment-btn').forEach(button => {
            button.addEventListener('click', async function () {
                const commentId = this.dataset.commentId;
                const approveUrl = this.dataset.approveUrl;
                const row = this.closest('tr');
                const statusSpan = row.querySelector('span[class*="rounded-full"]');

                try {
                    const data = await sendAjaxRequest(approveUrl, 'PUT');
                    if (data.success) {
                        Swal.fire(
                            'Đã phê duyệt!',
                            'Bình luận đã được phê duyệt thành công.',
                            'success'
                        );
                        // Update UI
                        statusSpan.textContent = 'Đã phê duyệt';
                        statusSpan.classList.remove('bg-yellow-100', 'text-yellow-800', 'dark:bg-yellow-800', 'dark:text-yellow-100');
                        statusSpan.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-800', 'dark:text-green-100');
                        this.remove(); // Remove the approve button
                    }
                } catch (error) {
                    // Error handled by sendAjaxRequest
                }
            });
        });

        // Handle Delete Button Click
        document.querySelectorAll('.delete-comment-btn').forEach(button => {
            button.addEventListener('click', function () {
                const commentId = this.dataset.commentId;
                const deleteUrl = this.dataset.deleteUrl;
                const row = this.closest('tr');

                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Bạn sẽ không thể hoàn tác hành động này!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Có, xóa nó!',
                    cancelButtonText: 'Hủy bỏ'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const data = await sendAjaxRequest(deleteUrl, 'DELETE');
                            if (data.success) {
                                Swal.fire(
                                    'Đã xóa!',
                                    'Bình luận đã được xóa thành công.',
                                    'success'
                                );
                                row.remove(); // Remove the entire row from the table
                            }
                        } catch (error) {
                            // Error handled by sendAjaxRequest
                        }
                    }
                });
            });
        });
    });
</script>
