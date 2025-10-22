<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi tiết bài đăng') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12"> {{-- Giảm padding top/bottom trên mobile --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4"> {{-- Thêm padding ngang mặc định cho mobile --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg shadow-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100 space-y-6 md:space-y-8">

                    {{-- Tiêu đề bài đăng --}}
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                            Tiêu đề: {{ $post->title }}
                        </h1>
                        <div class="text-sm text-gray-500 dark:text-gray-400 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0">
                            <span>Đăng bởi: <span class="font-medium">{{ $post->user->name ?? 'Ẩn danh' }}</span></span>
                            <span>Ngày đăng: <span class="font-medium">{{ $post->created_at->format('d/m/Y H:i') }}</span></span> {{-- Thêm H:i cho chi tiết --}}
                        </div>
                    </div>

                    {{-- Ảnh banner --}}
                    @if ($post->banner_image)
                        <div class="flex justify-center my-4 md:my-6">
                            <img src="{{ asset('storage/' . $post->banner_image) }}" alt="Banner Image"
                                 class="w-full md:w-2/3 lg:max-w-2xl h-auto object-contain rounded-lg shadow-md">
                                 {{-- w-full trên mobile, w-2/3 trên md, giới hạn max-w trên lg --}}
                        </div>
                    @endif

                    {{-- Nội dung bài đăng --}}
                    {{-- Tailwind prose class đã khá responsive. Đảm bảo nó không bị overflow. --}}
                    <div class="prose dark:prose-invert max-w-none prose-sm sm:prose-base lg:prose-lg xl:prose-xl break-words border-b border-gray-200 dark:border-gray-700 pb-4">
                        {!! $post->content !!}
                    </div>

                    {{-- Bộ sưu tập ảnh --}}
                    {{-- ===== ĐÃ SỬA LẠI BIẾN (Bỏ $galleryImages) ===== --}}
                    @if ($post->gallery_images && count($post->gallery_images) > 0)
                        <div>
                            <h3 class="text-xl md:text-2xl font-semibold mb-3 md:mb-4 text-gray-900 dark:text-gray-100">Bộ sưu tập ảnh</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-4">
                                @foreach ($post->gallery_images as $image)
                                    <div class="relative aspect-square">
                                        <a href="{{ asset('storage/' . $image) }}" data-lightbox="gallery" data-title="Hình ảnh trong thư viện">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image"
                                                 class="gallery-image w-full h-full object-cover rounded-lg shadow-md cursor-pointer hover:opacity-80 transition-opacity">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div>
                            <h3 class="text-xl md:text-2xl font-semibold mb-3 md:mb-4 text-gray-900 dark:text-gray-100">Bộ sưu tập ảnh</h3>
                            <p class="text-gray-600 dark:text-gray-300">Không có ảnh trong bộ sưu tập.</p>
                        </div>
                    @endif
                    {{-- ================================================ --}}

                    {{-- Hành động --}}
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
    @if(auth()->check() && auth()->user()->role !== 'User')
        
        {{-- Nút CHỈNH SỬA --}}
        <a href="{{ route('posts.edit', $post) }}"
        class="w-full sm:w-auto sm:min-w-28 
               inline-flex items-center justify-center 
               px-4 py-2 text-sm leading-tight border border-blue-500 text-blue-500 rounded-md 
               hover:bg-blue-500 hover:text-white transition-all">
            ✏️ Chỉnh sửa
        </a>
        
        {{-- FORM XÓA (Cần có ID để JavaScript tìm thấy) --}}
        <form id="delete-post-form" action="{{ route('posts.destroy', $post) }}" method="POST" class="w-full sm:w-auto">
            @csrf
            @method('DELETE')
        </form>

        {{-- Nút XÓA (Anchor tag gọi hàm với ID form) --}}
        <a href="#"
           class="w-full sm:w-auto sm:min-w-28 
                 inline-flex items-center justify-center 
                 px-4 py-2 text-sm leading-tight border border-red-500 text-red-500 rounded-md 
                 hover:bg-red-500 hover:text-white transition-all"
           onclick="return confirmDelete('delete-post-form', event);"> {{-- THAY ĐỔI LỚN NHẤT Ở ĐÂY --}}
            🗑️ Xóa
        </a>
    @endif
    
    {{-- Nút QUAY LẠI --}}
    <a href="{{ url()->previous() }}"
        class="w-full sm:w-auto sm:min-w-28 
               inline-flex items-center justify-center 
               px-4 py-2 text-sm leading-tight border border-gray-400 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md 
               hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
        ⬅️ Quay lại
    </a>
</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thêm thư viện Lightbox -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
    {{-- SweetAlert cho confirm xóa --}}


    <style>
        /* CSS tùy chỉnh nếu cần, Tailwind thường đủ */
        .prose h1, .prose h2, .prose h3 { /* Đảm bảo tiêu đề trong prose cũng có màu dark mode */
            color: inherit;
        }
        .prose img { /* Đảm bảo ảnh trong prose không bị tràn */
            max-width: 100%;
            height: auto;
            margin-left: auto;
            margin-right: auto;
            border-radius: 0.5rem; /* rounded-lg */
        }
        /* Lightbox options */
        .lightboxOverlay {
            background-color: rgba(0, 0, 0, 0.85) !important;
        }
    </style>

    <script>
    // Khởi tạo lightbox với tùy chọn (nếu cần)
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'fadeDuration': 300
    });

    // Hàm confirm xóa với SweetAlert đã được cập nhật
    function confirmDelete(formId, event) {
        event.preventDefault(); // Ngăn hành động mặc định của thẻ <a> (chuyển hướng)
        
        // SỬA: Lấy form bằng ID được truyền vào, thay vì dựa vào event.target.closest('form')
        const form = document.getElementById(formId); 

        Swal.fire({
            title: 'Bạn có chắc muốn xóa?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Có, xóa nó!',
            cancelButtonText: 'Hủy',
            customClass: {
                popup: 'dark:bg-gray-800 dark:text-gray-200',
                title: 'dark:text-gray-100',
                htmlContainer: 'dark:text-gray-300'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Gửi form nếu xác nhận
            }
        });
        return false; // Ngăn chặn hành động mặc định của onclick
    }
</script>
</x-app-layout>
