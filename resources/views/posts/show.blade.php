{{-- CẬP NHẬT FILE NÀY: resources/views/posts/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi tiết bài đăng (Admin)') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100 space-y-6 md:space-y-8">

                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                            Tiêu đề: {{ $post->title }}
                        </h1>
                        <div class="text-sm text-gray-500 dark:text-gray-400 flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                            <span>Đăng bởi: <span class="font-medium">{{ $post->user->name ?? 'Ẩn danh' }}</span></span>
                            <span>Ngày đăng: <span class="font-medium">{{ $post->created_at->format('d/m/Y H:i') }}</span></span>
                        </div>
                    </div>

                    @if ($post->banner_image)
                        <div class="flex justify-center my-4"><img src="{{ asset('storage/' . $post->banner_image) }}" alt="Banner Image" class="w-full md:w-2/3 lg:max-w-2xl h-auto object-contain rounded-lg shadow-md"></div>
                    @endif

                    <div class="prose dark:prose-invert max-w-none prose-lg break-words border-b border-gray-200 dark:border-gray-700 pb-4">{!! $post->content !!}</div>

                    @if ($post->gallery_images && count($post->gallery_images) > 0)
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Bộ sưu tập ảnh</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                @foreach ($post->gallery_images as $image)
                                    <div class="relative aspect-square"><a href="{{ asset('storage/' . $image) }}" data-lightbox="gallery"><img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" class="w-full h-full object-cover rounded-lg shadow-md"></a></div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Bộ sưu tập ảnh</h3>
                            <p class="text-gray-600 dark:text-gray-300">Không có ảnh trong bộ sưu tập.</p>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('posts.edit', $post) }}" class="w-full sm:w-auto sm:min-w-28 inline-flex items-center justify-center px-4 py-2 text-sm border border-blue-500 text-blue-500 rounded-md hover:bg-blue-500 hover:text-white transition-all">✏️ Chỉnh sửa</a>
                        <form id="delete-post-form" action="{{ route('posts.destroy', $post) }}" method="POST" class="w-full sm:w-auto"> @csrf @method('DELETE') </form>
                        <a href="#" class="w-full sm:w-auto sm:min-w-28 inline-flex items-center justify-center px-4 py-2 text-sm border border-red-500 text-red-500 rounded-md hover:bg-red-500 hover:text-white transition-all" onclick="return confirmDelete('delete-post-form', event);">🗑️ Xóa</a>
                        <a href="{{ route('posts.list') }}" class="w-full sm:w-auto sm:min-w-28 inline-flex items-center justify-center px-4 py-2 text-sm border border-gray-400 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">⬅️ Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
        <script>
            lightbox.option({ 'resizeDuration': 200, 'wrapAround': true, 'fadeDuration': 300 });
            function confirmDelete(formId, event) {
                event.preventDefault(); 
                const form = document.getElementById(formId); 
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa?', text: "Hành động này không thể hoàn tác!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Có, xóa nó!', cancelButtonText: 'Hủy', background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff', color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827'
                }).then((result) => { if (result.isConfirmed) { form.submit(); } });
                return false;
            }
        </script>
    @endpush
</x-app-layout>