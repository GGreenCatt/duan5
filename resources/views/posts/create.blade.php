<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tạo bài viết mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form id="create-post-form" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Cột chính (2/3) --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Card cho nội dung chính --}}
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                            {{-- Tiêu đề --}}
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-medium text-gray-300 required">Tiêu đề</label>
                                <input type="text" id="title" name="title"
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-white"
                                    required maxlength="100" value="{{ old('title') }}">
                                <small class="text-red-500 error-message mt-1" id="title-error" style="display: none;"></small>
                            </div>

                            {{-- Mô tả ngắn --}}
                            <div class="mb-6">
                                <label for="short_description" class="block text-sm font-medium text-gray-300 required">Mô tả ngắn</label>
                                <textarea id="short_description" name="short_description" rows="4"
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-white"
                                    required maxlength="200">{{ old('short_description') }}</textarea>
                                <small class="text-red-500 error-message mt-1" id="short_description-error" style="display: none;"></small>
                            </div>

                            {{-- Nội dung --}}
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-300 required">Nội dung</label>
                                <div class="mt-1">
                                    <textarea id="content" name="content" class="ckeditor">{{ old('content') }}</textarea>
                                </div>
                                <small class="text-red-500 error-message mt-1" id="content-error" style="display: none;"></small>
                            </div>
                        </div>
                    </div>

                    {{-- Cột phụ (1/3) --}}
                    <div class="lg:col-span-1 space-y-6">
                        {{-- Card cho việc xuất bản --}}
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                             <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-700 pb-3">Xuất bản</h3>
                             <div class="flex items-center justify-between">
                                <button type="button" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white text-sm font-medium rounded-md">
                                    Lưu nháp
                                </button>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    Đăng bài
                                </button>
                            </div>
                        </div>

                        {{-- Card cho danh mục --}}
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-700 pb-3">Danh mục</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="parent_category" class="block text-sm font-medium text-gray-300 required">Danh mục cha</label>
                                    <select id="parent_category" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-white">
                                        <option value="">-- Chọn danh mục cha --</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-red-500 error-message mt-1" id="parent_category-error" style="display: none;"></small>
                                </div>
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-300 required">Danh mục con</label>
                                    <select name="category_id" id="category_id" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-white" disabled>
                                        <option value="">-- Vui lòng chọn danh mục cha --</option>
                                        @foreach($allCategories as $cat)
                                            @if($cat->parent_id)
                                                <option value="{{ $cat->id }}" data-parent="{{ $cat->parent_id }}" class="hidden">{{ $cat->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="text-red-500 error-message mt-1" id="category_id-error" style="display: none;"></small>
                                </div>
                            </div>
                        </div>

                        {{-- Card cho Banner --}}
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                             <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-700 pb-3">Banner ảnh</h3>
                             <div id="banner-upload-zone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-md cursor-pointer hover:border-indigo-500 transition">
                                 <div class="space-y-1 text-center">
                                     <svg class="mx-auto h-12 w-12 text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                         <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                     </svg>
                                     <div class="flex text-sm text-gray-500">
                                         <label for="banner_image" class="relative cursor-pointer bg-gray-800 rounded-md font-medium text-indigo-400 hover:text-indigo-300 focus-within:outline-none">
                                             <span>Tải lên một tệp</span>
                                             <input id="banner_image" name="banner_image" type="file" class="sr-only" accept="image/*">
                                         </label>
                                         <p class="pl-1">hoặc kéo và thả</p>
                                     </div>
                                     <p class="text-xs text-gray-600">PNG, JPG, GIF tối đa 2MB</p>
                                 </div>
                             </div>
                             <div id="banner_preview" class="mt-4"></div>
                             <small class="text-red-500 error-message mt-1" id="banner_image-error" style="display: none;"></small>
                        </div>

                        {{-- Card cho Gallery --}}
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                             <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-700 pb-3">Thư viện ảnh</h3>
                             <div id="gallery-upload-zone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-md cursor-pointer hover:border-indigo-500 transition">
                                <div class="space-y-1 text-center">
                                     <svg class="mx-auto h-12 w-12 text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                     <div class="flex text-sm text-gray-500">
                                         <label for="gallery_images" class="relative cursor-pointer bg-gray-800 rounded-md font-medium text-indigo-400 hover:text-indigo-300 focus-within:outline-none">
                                             <span>Tải lên các tệp</span>
                                             <input id="gallery_images" name="gallery_images[]" type="file" class="sr-only" multiple accept="image/*">
                                         </label>
                                         <p class="pl-1">hoặc kéo và thả</p>
                                     </div>
                                     <p class="text-xs text-gray-600">Chọn 2-5 ảnh, mỗi ảnh < 2MB</p>
                                 </div>
                             </div>
                             <div id="gallery_preview" class="mt-4 grid grid-cols-3 gap-4"></div>
                             <small class="text-red-500 error-message mt-1" id="gallery_images-error" style="display: none;"></small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert & CKEditor --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

    {{-- Custom Styles --}}
    <style>
        label.required::after {
            content: " *";
            color: #ef4444; /* red-500 */
        }
        .ck-editor__editable_inline {
            min-height: 250px;
            color: #333;
        }
    </style>

    {{-- Custom Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- CKEditor Initialization ---
            CKEDITOR.replace('content', {
                uiColor: '#374151', // gray-700
                // You can add more CKEditor configurations here
            });

            // --- Form state tracking ---
            let isFormChanged = false;
            let isFormSubmitted = false;
            const form = document.getElementById('create-post-form');

            form.addEventListener('input', () => isFormChanged = true);
            form.addEventListener('change', () => isFormChanged = true);
            CKEDITOR.instances.content.on('change', () => isFormChanged = true);

            // --- Category Dropdown Logic ---
            const parentSelect = document.getElementById('parent_category');
            const childSelect = document.getElementById('category_id');
            const childError = document.getElementById('category_id-error');
            const allChildOptions = Array.from(childSelect.options);

            parentSelect.addEventListener('change', function () {
                const selectedParentId = this.value;
                let hasVisibleChildOptions = false;

                // Reset child select
                childSelect.value = "";
                childSelect.disabled = !selectedParentId;
                childSelect.innerHTML = `<option value="">-- ${selectedParentId ? 'Chọn danh mục con' : 'Vui lòng chọn danh mục cha'} --</option>`;

                allChildOptions.forEach(opt => {
                    if (opt.dataset.parent === selectedParentId) {
                        childSelect.appendChild(opt);
                        opt.classList.remove('hidden');
                        hasVisibleChildOptions = true;
                    } else if (opt.value !== "") {
                        opt.classList.add('hidden');
                    }
                });

                if (selectedParentId && !hasVisibleChildOptions) {
                    childError.innerText = "*(Không có danh mục con cho mục cha đã chọn.)";
                    childError.style.display = 'block';
                    childSelect.disabled = true;
                } else if (!selectedParentId) {
                    childError.innerText = "*(Vui lòng chọn danh mục cha trước.)";
                    childError.style.display = 'block';
                } else {
                    childError.style.display = 'none';
                }
            });

            // --- Drag & Drop for File Uploads ---
            function setupDragDrop(zoneId, inputId) {
                const zone = document.getElementById(zoneId);
                const input = document.getElementById(inputId);

                zone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    zone.classList.add('border-indigo-500');
                });
                zone.addEventListener('dragleave', () => {
                    zone.classList.remove('border-indigo-500');
                });
                zone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    zone.classList.remove('border-indigo-500');
                    input.files = e.dataTransfer.files;
                    input.dispatchEvent(new Event('change')); // Trigger change event for preview
                });
                 zone.addEventListener('click', () => {
                    input.click();
                });
            }
            setupDragDrop('banner-upload-zone', 'banner_image');
            setupDragDrop('gallery-upload-zone', 'gallery_images');


            // --- Image Preview Functions ---
            const bannerInput = document.getElementById('banner_image');
            const galleryInput = document.getElementById('gallery_images');
            const bannerPreview = document.getElementById('banner_preview');
            const galleryPreview = document.getElementById('gallery_preview');
            const bannerError = document.getElementById('banner_image-error');
            const galleryError = document.getElementById('gallery_images-error');

            bannerInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                bannerPreview.innerHTML = '';
                bannerError.style.display = 'none';
                if (!file) return;

                if (file.size > 2 * 1024 * 1024) { // 2MB
                    bannerError.innerText = "*(Banner ảnh không được vượt quá 2MB.)";
                    bannerError.style.display = 'block';
                    event.target.value = "";
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => bannerPreview.innerHTML = `<img src="${e.target.result}" class="max-h-48 rounded-lg mx-auto shadow-md" />`;
                reader.readAsDataURL(file);
            });

            galleryInput.addEventListener('change', function(event) {
                const files = event.target.files;
                galleryPreview.innerHTML = '';
                galleryError.style.display = 'none';

                if (files.length === 0) return;
                if (files.length < 2 || files.length > 5) {
                    galleryError.innerText = "*(Phải chọn từ 2 đến 5 ảnh.)";
                    galleryError.style.display = 'block';
                    event.target.value = "";
                    return;
                }
                Array.from(files).forEach(file => {
                    if (file.size > 2 * 1024 * 1024) {
                        galleryError.innerText = `*(Ảnh "${file.name}" vượt quá 2MB.)`;
                        galleryError.style.display = 'block';
                        event.target.value = "";
                        galleryPreview.innerHTML = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        galleryPreview.innerHTML += `<div class="relative"><img src="${e.target.result}" class="h-24 w-24 object-cover rounded-lg shadow-md" /></div>`;
                    };
                    reader.readAsDataURL(file);
                });
            });


            // --- Form Validation & Submission ---
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                if (isFormSubmitted) return;

                if (!validateForm()) {
                    return;
                }

                Swal.fire({
                    title: 'Xác nhận đăng bài?',
                    text: "Bài viết sẽ được công khai sau khi đăng.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Đăng bài',
                    cancelButtonText: 'Xem lại',
                    background: '#1f2937', // dark:bg-gray-800
                    color: '#f3f4f6'     // dark:text-gray-300
                }).then((result) => {
                    if (result.isConfirmed) {
                        isFormSubmitted = true;
                        window.removeEventListener("beforeunload", handleBeforeUnload);
                        CKEDITOR.instances.content.updateElement(); // Update textarea before submit
                        form.submit();
                    }
                });
            });

            function validateForm() {
                let isValid = true;
                const fields = [
                    { id: 'title', name: 'Tiêu đề', max: 100 },
                    { id: 'short_description', name: 'Mô tả ngắn', max: 200 },
                    { id: 'parent_category', name: 'Danh mục cha' },
                    { id: 'category_id', name: 'Danh mục con' },
                    { id: 'banner_image', name: 'Banner ảnh' },
                    { id: 'gallery_images', name: 'Thư viện ảnh' }
                ];

                // Clear previous errors
                document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');

                for (const field of fields) {
                    const el = document.getElementById(field.id);
                    const errorEl = document.getElementById(`${field.id}-error`);

                    if (!el.value || (el.type === 'file' && el.files.length === 0)) {
                        errorEl.innerText = `*(${field.name} là bắt buộc.)`;
                        errorEl.style.display = 'block';
                        isValid = false;
                    }
                }

                // CKEditor validation
                const contentData = CKEDITOR.instances.content.getData();
                if (contentData.trim() === '') {
                    document.getElementById('content-error').innerText = '*(Nội dung không được để trống.)';
                    document.getElementById('content-error').style.display = 'block';
                    isValid = false;
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thiếu thông tin',
                        text: 'Vui lòng điền đầy đủ các trường bắt buộc.',
                        background: '#1f2937',
                        color: '#f3f4f6'
                    });
                }

                return isValid;
            }

            // --- Unload Warning ---
            function handleBeforeUnload(e) {
                if (isFormChanged && !isFormSubmitted) {
                    e.preventDefault();
                    e.returnValue = ''; // Required for cross-browser compatibility
                }
            }
            window.addEventListener("beforeunload", handleBeforeUnload);
        });
    </script>
</x-app-layout>