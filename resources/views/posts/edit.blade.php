<head>
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
</head>
<style>
    /* Định dạng ảnh và nút X */
    .relative img {
        transition: transform 0.3s;
    }

    .relative:hover img {
        opacity: 0.8;
        transform: scale(1.05);
    }

    .relative .delete-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 16px;
        font-weight: bold;
        width: 24px;
        height: 24px;
        text-align: center;
        line-height: 24px;
        border-radius: 50%;
        cursor: pointer;
        display: none;
        transition: all 0.3s;
    }

    .relative:hover .delete-btn {
        display: block;
    }
</style>
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chỉnh sửa bài viết') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 mb-4 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-white">Tiêu đề</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full bg-gray-800 text-white" value="{{ old('title', $post->title) }}" required maxlength="200" />
                        </div>

                        <div class="mb-4">
                            <label for="short_description" class="block text-sm font-medium text-white">Mô tả ngắn</label>
                            <textarea id="short_description" name="short_description" rows="4" class="mt-1 block w-full bg-gray-800 text-white" required maxlength="200">{{ old('short_description', $post->short_description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-white">Nội dung</label>
                            <textarea id="content" name="content" rows="10" class="mt-1 block w-full bg-gray-800 text-white" maxlength="500">{{ old('content', $post->content) }}</textarea>
                        </div>

                        <!-- Phần Banner Ảnh -->
                        <div class="mb-4">
                            <label for="banner_image" class="block text-sm font-medium text-white">Banner ảnh</label>
                            <input type="file" id="banner_image" name="banner_image" class="mt-1 block w-full bg-gray-800 text-white" />
                            @if ($post->banner_image)
                                <div class="mt-2 relative" style="width: 128px; height: 128px;">
                                    <img src="{{ asset('storage/' . $post->banner_image) }}" alt="Banner hiện tại" class="w-full h-full object-cover rounded">
                                    <span class="delete-btn" onclick="deleteBannerImage()">×</span>
                                </div>
                            @endif
                        </div>

                        <!-- Phần Thư viện ảnh -->
                        <div class="mb-4">
                            <label for="gallery_images" class="block text-sm font-medium text-white">Ảnh thư viện</label>
                            <input type="file" id="gallery_images" name="gallery_images[]" class="mt-1 block w-full bg-gray-800 text-white" multiple />
                            @if ($post->gallery_images)
                                <div class="mt-2 flex gap-2" id="gallery-container">
                                    @foreach (json_decode($post->gallery_images, true) as $image)
                                        <div class="relative gallery-item" data-image="{{ $image }}" style="width: 64px; height: 64px;">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery" class="w-full h-full object-cover rounded">
                                            <span class="delete-btn" onclick="deleteGalleryImage('{{ $image }}')">×</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <x-primary-button>Cập nhật bài viết</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        CKEDITOR.replace('content');
    </script>
    <script>
    // Xóa banner ảnh
    function deleteBannerImage() {
        if (confirm('Bạn có chắc muốn xóa banner ảnh này?')) {
            fetch("{{ route('posts.deleteBanner', $post->id) }}", {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Xóa banner thành công');
                    location.reload();
                }
            });
        }
    }

    // Xóa ảnh trong thư viện
    function deleteGalleryImage(image) {
        if (confirm('Bạn có chắc muốn xóa ảnh này?')) {
            fetch("{{ route('posts.deleteGallery', $post->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ image: image })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Xóa ảnh thành công');
                    document.querySelector(`.gallery-item[data-image="${image}"]`).remove();
                }
            });
        }
    }
</script>
</x-app-layout>
