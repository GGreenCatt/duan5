<head>
    {{-- Các script này nên nằm trong layout chính app.blade.php để tối ưu, nhưng giữ lại theo yêu cầu của bạn --}}
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
    .relative img { transition: transform 0.3s; }
    .relative:hover img { opacity: 0.8; transform: scale(1.05); }
    .relative .delete-btn { position: absolute; top: 5px; right: 5px; background: rgba(0, 0, 0, 0.6); color: white; font-size: 16px; font-weight: bold; width: 24px; height: 24px; text-align: center; line-height: 24px; border-radius: 50%; cursor: pointer; display: none; transition: all 0.3s; }
    .relative:hover .delete-btn { display: block; }
    label.required::after { content: " *"; color: red; font-weight: bold; }
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

                    <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-white required">Tiêu đề</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full bg-gray-800 text-white"
                                value="{{ old('title', $post->title) }}" required maxlength="255" />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="short_description" class="block text-sm font-medium text-white required">Mô tả ngắn</label>
                            <textarea id="short_description" name="short_description" rows="4" class="mt-1 block w-full bg-gray-800 text-white"
                                required maxlength="1000">{{ old('short_description', $post->short_description) }}</textarea>
                            @error('short_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-white required">Nội dung</label>
                            <textarea id="content" name="content" rows="10" class="mt-1 block w-full bg-gray-800 text-white"
                                maxlength="3000">{{ old('content', $post->content)}}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-white required">Danh mục</label>
                            <select id="category_id" name="category_id" class="mt-1 block w-full bg-gray-800 text-white" required>
                                <option value="" disabled {{ !old('category_id', $post->category_id) ? 'selected' : '' }}>-- Chọn danh mục --</option>
                                {{-- Giả sử controller gửi biến $categories là danh sách phẳng các danh mục con --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="banner_image" class="block text-sm font-medium text-white">Banner ảnh</label>
                            <input type="file" id="banner_image" name="banner_image"
                                class="mt-1 block w-full bg-gray-800 text-white" />
                            @if ($post->banner_image)
                                <div id="banner-container" class="mt-2 relative" style="width: 128px; height: 128px;">
                                    <img src="{{ asset('storage/' . $post->banner_image) }}" alt="Banner hiện tại"
                                        class="w-full h-full object-cover rounded">
                                    <span class="delete-btn" onclick="deleteBannerImage()">×</span>
                                </div>
                            @endif
                            @error('banner_image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="gallery_images" class="block text-sm font-medium text-white">Ảnh thư viện</label>
                            <input type="file" id="gallery_images" name="gallery_images[]"
                                class="mt-1 block w-full bg-gray-800 text-white" multiple />
                            
                            {{-- ===== ĐÃ SỬA LẠI LOGIC NÀY (BỎ json_decode) ===== --}}
                            @if ($post->gallery_images && is_array($post->gallery_images) && count($post->gallery_images) > 0)
                                <div class="mt-2 flex gap-2 flex-wrap" id="gallery-container">
                                    @foreach ($post->gallery_images as $image)
                                        <div class="relative gallery-item" data-image="{{ $image }}"
                                            style="width: 64px; height: 64px;">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery"
                                                class="w-full h-full object-cover rounded">
                                            <span class="delete-btn" onclick="deleteGalleryImage('{{ $image }}')">×</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            {{-- ================================================= --}}

                             @error('gallery_images.*')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-primary-button type="button" id="confirmUpdate">Cập nhật bài viết</x-primary-button>
                        <x-danger-button type="button" id="cancelEdit" class="ml-2">Hủy chỉnh sửa</x-danger-button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        CKEDITOR.replace('content');

        let formChanged = false;

        document.querySelectorAll('input, textarea, select').forEach(el => {
            el.addEventListener('input', () => { formChanged = true; });
            if (el.type === 'file') { el.addEventListener('change', () => { formChanged = true; }); }
        });

        if (CKEDITOR.instances['content']) {
            CKEDITOR.instances['content'].on('change', function () { formChanged = true; });
        }

        function handleBeforeUnload(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        }
        window.addEventListener('beforeunload', handleBeforeUnload);

        document.querySelectorAll('a:not([href^="#"]):not([onclick])').forEach(link => {
            link.addEventListener('click', function (e) {
                if (link.closest('form') || link.id === 'confirmUpdate' || link.id === 'cancelEdit' || link.classList.contains('delete-btn')) {
                    return;
                }
                if (formChanged) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Bạn có chắc muốn rời khỏi trang?', text: "Mọi thay đổi chưa được lưu sẽ mất.", icon: 'warning', showCancelButton: true, confirmButtonText: 'Rời trang', cancelButtonText: 'Ở lại'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formChanged = false;
                            window.removeEventListener('beforeunload', handleBeforeUnload);
                            window.location.href = link.href;
                        }
                    });
                }
            });
        });

        document.getElementById('confirmUpdate').addEventListener('click', function (e) {
            e.preventDefault();
            window.removeEventListener('beforeunload', handleBeforeUnload);
            Swal.fire({
                title: 'Xác nhận cập nhật?', text: "Bạn có chắc muốn lưu các thay đổi?", icon: 'question', showCancelButton: true, confirmButtonText: 'Có, cập nhật', cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    formChanged = false;
                    if (CKEDITOR.instances.content) {
                        CKEDITOR.instances.content.updateElement();
                    }
                    document.getElementById('editForm').submit();
                } else {
                    window.addEventListener('beforeunload', handleBeforeUnload);
                }
            });
        });

        document.getElementById('cancelEdit').addEventListener('click', function (e) {
            e.preventDefault();
            if (formChanged) {
                Swal.fire({
                    title: 'Hủy chỉnh sửa?', text: 'Tất cả thay đổi chưa lưu sẽ bị mất.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Có, hủy bỏ', cancelButtonText: 'Tiếp tục chỉnh sửa'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formChanged = false;
                        window.removeEventListener('beforeunload', handleBeforeUnload);
                        window.location.href = '{{ route("posts.list") }}';
                    }
                });
            } else {
                window.removeEventListener('beforeunload', handleBeforeUnload);
                window.location.href = '{{ route("posts.list") }}';
            }
        });

        function deleteBannerImage() {
            Swal.fire({
                title: 'Bạn chắc chắn xóa banner?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Xóa', cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('posts.deleteBanner', $post->id) }}", {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Đã xóa!', 'Banner đã được xóa.', 'success').then(() => {
                                document.getElementById('banner-container')?.remove();
                                formChanged = true;
                            });
                        } else { Swal.fire('Lỗi!', data.message || 'Không thể xóa banner.', 'error'); }
                    }).catch(error => Swal.fire('Lỗi!', 'Có lỗi xảy ra khi xóa banner.', 'error'));
                }
            });
        }

        function deleteGalleryImage(imageName) {
            Swal.fire({
                title: 'Xóa ảnh này?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Xóa', cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('posts.deleteGallery', $post->id) }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', },
                        body: JSON.stringify({ image: imageName, _method: 'DELETE' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Đã xóa!', 'Ảnh đã được xóa khỏi thư viện.', 'success');
                            document.querySelector(`.gallery-item[data-image="${imageName}"]`)?.remove();
                            formChanged = true;
                        } else { Swal.fire('Lỗi!', data.message || 'Không thể xóa ảnh.', 'error'); }
                    }).catch(error => Swal.fire('Lỗi!', 'Có lỗi xảy ra khi xóa ảnh.', 'error'));
                }
            });
        }
    </script>
</x-app-layout>