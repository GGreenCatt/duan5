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
                        {{-- ===== CẬP NHẬT: Thêm các input hidden để lưu trạng thái xóa ảnh ===== --}}
                        <input type="hidden" name="deleted_banner" id="deleted_banner" value="0">
                        <div id="deleted-gallery-container"></div>
                        {{-- ================================================================= --}}
                        
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
                            <input type="file" id="banner_image" name="banner_image" class="mt-1 block w-full bg-gray-800 text-white" accept="image/*" />
                            <div id="banner-display-area" class="mt-2">
                                @if ($post->banner_image)
                                    <div id="banner-container" class="relative" style="width: 128px; height: 128px;">
                                        <img src="{{ asset('storage/' . $post->banner_image) }}" alt="Banner hiện tại" class="w-full h-full object-cover rounded">
                                        <span class="delete-btn" onclick="deleteBannerImage(event)">×</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="gallery_images" class="block text-sm font-medium text-white">Ảnh thư viện</label>
                            <input type="file" id="gallery_images" name="gallery_images[]" class="mt-1 block w-full bg-gray-800 text-white" multiple accept="image/*" />
                            <p id="new-gallery-heading" class="text-sm text-gray-400 mt-4" style="display: none;">Ảnh mới chọn (có thể xóa):</p>
                            <div id="gallery-preview-container" class="mt-2 flex gap-2 flex-wrap"></div>
                            @if ($post->gallery_images && count($post->gallery_images) > 0)
                                <p class="text-sm text-gray-400 mt-4">Ảnh hiện tại:</p>
                                <div class="mt-2 flex gap-2 flex-wrap" id="gallery-container">
                                    @foreach ($post->gallery_images as $image)
                                        <div class="relative gallery-item" data-image="{{ $image }}" style="width: 64px; height: 64px;">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery" class="w-full h-full object-cover rounded">
                                            <span class="delete-btn" onclick="deleteGalleryImage(event, '{{ $image }}')">×</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <x-primary-button type="button" id="confirmUpdate">Cập nhật bài viết</x-primary-button>
                        <x-danger-button type="button" id="cancelEdit" class="ml-2">Hủy chỉnh sửa</x-danger-button>

                    </form>
                </div>
            </div>
        </div>
    </div>
<style> 
    .cke_notifications_area {
    display: none !important;}
</style>
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

        document.addEventListener('DOMContentLoaded', function() {
            const bannerInput = document.getElementById('banner_image');
            const bannerDisplayArea = document.getElementById('banner-display-area');
            
            bannerInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                bannerDisplayArea.innerHTML = ''; 
                if (file) {
                    document.getElementById('deleted_banner').value = '0';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        bannerDisplayArea.innerHTML = `
                            <div class="relative" style="width: 128px; height: 128px;">
                                <img src="${e.target.result}" class="w-full h-full object-cover rounded">
                                <span class="delete-btn" style="display: block;" onclick="removeNewBannerImage(event)">×</span>
                            </div>
                        `;
                    }
                    reader.readAsDataURL(file);
                }
            });

            window.removeNewBannerImage = function(event) {
                event.stopPropagation();
                bannerInput.value = '';
                bannerDisplayArea.innerHTML = '';
                formChanged = true;
            }

            const galleryInput = document.getElementById('gallery_images');
            const galleryPreviewContainer = document.getElementById('gallery-preview-container');
            const newGalleryHeading = document.getElementById('new-gallery-heading');

            galleryInput.addEventListener('change', function() {
                renderGalleryPreview();
            });
            
            function renderGalleryPreview() {
                galleryPreviewContainer.innerHTML = '';
                const files = galleryInput.files;

                if (files.length > 0) {
                    newGalleryHeading.style.display = 'block';
                    Array.from(files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative gallery-item-new';
                            div.style.width = '64px';
                            div.style.height = '64px';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-full object-cover rounded">
                                <span class="delete-btn" style="display: block;" onclick="removeNewGalleryImage(event, ${index})">×</span>
                            `;
                            galleryPreviewContainer.appendChild(div);
                        }
                        reader.readAsDataURL(file);
                    });
                } else {
                    newGalleryHeading.style.display = 'none';
                }
            }

            window.removeNewGalleryImage = function(event, index) {
                event.stopPropagation();
                const dt = new DataTransfer();
                const files = galleryInput.files;
                
                for (let i = 0; i < files.length; i++) {
                    if (i !== index) {
                        dt.items.add(files[i]);
                    }
                }
                
                galleryInput.files = dt.files;
                formChanged = true;
                renderGalleryPreview();
            }
        });

        // ===== CẬP NHẬT: Hàm xóa ảnh client-side =====
        function deleteBannerImage(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Bạn chắc chắn xóa banner?',
                text: "Hành động này sẽ xóa ảnh sau khi bạn cập nhật.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleted_banner').value = '1';
                    document.getElementById('banner-container').style.display = 'none';
                    formChanged = true;
                    // Nếu người dùng chọn file mới rồi lại xóa banner cũ, thì cũng clear file input
                    const bannerInput = document.getElementById('banner_image');
                    if(bannerInput.files.length > 0){
                       bannerInput.value = '';
                       document.getElementById('banner-display-area').innerHTML = '';
                    }
                }
            });
        }

        function deleteGalleryImage(event, imageName) {
            event.preventDefault();
             Swal.fire({
                title: 'Xóa ảnh này?',
                text: "Hành động này sẽ xóa ảnh sau khi bạn cập nhật.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Thêm một input hidden để đánh dấu ảnh cần xóa
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'deleted_gallery_images[]';
                    hiddenInput.value = imageName;
                    document.getElementById('deleted-gallery-container').appendChild(hiddenInput);

                    // Ẩn ảnh khỏi giao diện
                    const imageElement = document.querySelector(`.gallery-item[data-image="${imageName}"]`);
                    if(imageElement) {
                        imageElement.style.display = 'none';
                    }
                    formChanged = true;
                }
            });
        }
    </script>
</x-app-layout>