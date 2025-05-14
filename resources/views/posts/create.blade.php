<head>
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tạo bài viết mới') }}
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

                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-white">Tiêu đề</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full bg-gray-800 text-white" required maxlength="200" />
                        </div>

                        <div class="mb-4">
                            <label for="short_description" class="block text-sm font-medium text-white">Mô tả ngắn</label>
                            <textarea id="short_description" name="short_description" rows="4" class="mt-1 block w-full bg-gray-800 text-white" required maxlength="200"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-white">Nội dung</label>
                            <textarea id="content" name="content" rows="10" class="mt-1 block w-full bg-gray-800 text-white" required maxlength="500"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="banner_image" class="block text-sm font-medium text-white">Banner ảnh</label>
                            <input type="file" id="banner_image" name="banner_image" class="mt-1 block w-full bg-gray-800 text-white" onchange="previewBannerImage(event)" />
                            <div id="banner_preview" class="mt-2"></div>
                        </div>

                        <div class="mb-4">
                            <label for="gallery_images" class="block text-sm font-medium text-white">Ảnh thư viện</label>
                            <input type="file" id="gallery_images" name="gallery_images[]" class="mt-1 block w-full bg-gray-800 text-white" multiple onchange="previewGalleryImages(event)" />
                            <div id="gallery_preview" class="mt-2 flex space-x-2"></div>
                        </div>

                        <x-primary-button>Đăng bài</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        CKEDITOR.replace('content');

        // Preview banner image
        function previewBannerImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('banner_preview');
                preview.innerHTML = `<img src="${reader.result}" class="w-32 h-32 object-cover rounded-lg" />`;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Preview multiple gallery images
        function previewGalleryImages(event) {
            const previewContainer = document.getElementById('gallery_preview');
            previewContainer.innerHTML = ''; // Clear previous previews
            const files = event.target.files;

            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function() {
                    const imageElement = document.createElement('img');
                    imageElement.src = reader.result;
                    imageElement.classList.add('w-20', 'h-20', 'object-cover', 'rounded-lg');
                    previewContainer.appendChild(imageElement);
                };
                reader.readAsDataURL(files[i]);
            }
        }
    </script>
</x-app-layout>
