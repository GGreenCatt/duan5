<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Moved SweetAlert here for earlier availability --}}
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
                        <script>
                            // This can be kept, or you can use a global SweetAlert instance
                            // if you prefer to initialize it once.
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: '{{ session('success') }}',
                                    confirmButtonText: 'OK'
                                });
                            });
                        </script>
                    @endif

                    {{-- REMOVED onsubmit="return validatePostForm(event)" --}}
                    <form id="create-post-form" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-white required">Tiêu đề</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full bg-gray-800 text-white" required maxlength="255" oninput="checkLength(this)">
                            <small class="text-red-500 error-message" id="title-error" style="display: none;"></small>
                        </div>

                        <div class="mb-4">
                            <label for="short_description" class="block text-sm font-medium text-white required">Mô tả ngắn</label>
                            <textarea id="short_description" name="short_description" rows="4" class="mt-1 block w-full bg-gray-800 text-white" required maxlength="1000" oninput="checkLength(this)"></textarea>
                            <small class="text-red-500 error-message" id="short_description-error" style="display: none;"></small>
                        </div>

                        <div class="mb-4">
                            <label for="parent_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 required" >Danh mục cha</label>
                            <select id="parent_category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" style="color: white; background-color: #333;">
                                <option value="">-- Chọn danh mục cha --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                             <small class="text-red-500 error-message" id="parent_category-error" style="display: none;"></small>
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 required">Danh mục con</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"style="color: white; background-color: #333;" disabled>
                                <option value="">-- Chọn danh mục con --</option>
                                @foreach($allCategories as $cat)
                                    @if($cat->parent_id)
                                        <option value="{{ $cat->id }}" data-parent="{{ $cat->parent_id }}">{{ $cat->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                             <small class="text-red-500 error-message" id="category_id-error" style="display: none;">*(Vui lòng chọn danh mục cha trước.)</small>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-white required">Nội dung</label>
                            {{-- maxlength on textarea for CKEditor is indicative, real check is in JS --}}
                            <textarea id="content" name="content" rows="10" class="mt-1 block w-full bg-gray-800 text-white" required></textarea>
                            <small class="text-red-500 error-message" id="content-error" style="display: none;"></small>
                        </div>

                        <div class="mb-4">
                            <label for="banner_image" class="block text-sm font-medium text-white required">Banner ảnh</label>
                            <input required type="file" id="banner_image" name="banner_image" class="mt-1 block w-full bg-gray-800 text-white" onchange="previewBannerImage(event)" accept="image/*">
                            <small class="text-red-500 error-message" id="banner_image-error" style="display: none;"></small>
                            <div id="banner_preview" class="mt-2"></div>
                        </div>

                        <div class="mb-4">
                            <label for="gallery_images" class="block text-sm font-medium text-white required">Ảnh thư viện (Chọn 2-5 ảnh, mỗi ảnh < 2MB)</label>
                            <input required type="file" id="gallery_images" name="gallery_images[]" class="mt-1 block w-full bg-gray-800 text-white" multiple onchange="previewGalleryImages(event)" accept="image/*">
                            <small class="text-red-500 error-message" id="gallery_images-error" style="display: none;"></small>
                            <div id="gallery_preview" class="mt-2 flex flex-wrap gap-2"></div> {{-- Added flex-wrap and gap --}}
                        </div>

                        <x-primary-button type="submit">Đăng bài</x-primary-button>
                        {{-- Removed redundant CKEditor update script. It will be handled in the main form submit listener. --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>
{{-- Removed redundant CKEditor update and validation script. Validation is in validatePostForm(), update is in the main submit handler. --}}

<script>
    // Real-time length check for CKEditor (HTML length)
    if (CKEDITOR.instances.content) {
        CKEDITOR.instances.content.on('change', function() {
            const editor = CKEDITOR.instances.content;
            const editorData = editor.getData(); // HTML content
            const maxLength = 3000; // The limit for HTML content
            const errorMessageElement = document.getElementById('content-error');

            if (editorData.length > maxLength) {
                errorMessageElement.style.display = 'block';
                errorMessageElement.innerText = `Nội dung (bao gồm cả định dạng) quá dài. Hiện tại: ${editorData.length} ký tự. Giới hạn: ${maxLength} ký tự.`;
            } else if (editorData.length === maxLength) {
                 errorMessageElement.style.display = 'block';
                 errorMessageElement.innerText = `Bạn đã đạt giới hạn ${maxLength} ký tự cho nội dung (bao gồm định dạng).`;
            }
            else {
                errorMessageElement.style.display = 'none';
                errorMessageElement.innerText = '';
            }
        });
    }


    // Preview banner image with size validation
    function previewBannerImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('banner_preview');
        const errorMessage = document.getElementById('banner_image-error');

        preview.innerHTML = ''; // Clear previous preview
        errorMessage.style.display = 'none'; // Clear previous error

        if (!file) return;

        if (file.size > 2 * 1024 * 1024) { // 2MB
            errorMessage.innerText = "*(Banner ảnh không được vượt quá 2MB.)";
            errorMessage.style.display = 'block';
            event.target.value = ""; // Clear the selected file
            return;
        }

        const reader = new FileReader();
        reader.onload = function() {
            preview.innerHTML = `<img src="${reader.result}" class="w-32 h-32 object-cover rounded-lg" />`;
        };
        reader.readAsDataURL(file);
    }

    // Preview multiple gallery images with count and size validation
    function previewGalleryImages(event) {
        const previewContainer = document.getElementById('gallery_preview');
        previewContainer.innerHTML = ''; // Clear previous previews
        const files = event.target.files;
        const errorMessage = document.getElementById('gallery_images-error');
        errorMessage.style.display = 'none'; // Clear previous error

        if (files.length === 0) return;

        if (files.length < 2 || files.length > 5) {
            errorMessage.innerText = "*(Bạn phải chọn ít nhất 2 ảnh và không quá 5 ảnh cho thư viện.)";
            errorMessage.style.display = 'block';
            event.target.value = ""; // Clear selection
            return;
        }

        for (let i = 0; i < files.length; i++) {
            if (files[i].size > 2 * 1024 * 1024) { // 2MB
                errorMessage.innerText = `*(Ảnh "${files[i].name}" trong thư viện vượt quá 2MB. Mỗi ảnh phải < 2MB.)`;
                errorMessage.style.display = 'block';
                event.target.value = ""; // Clear selection
                previewContainer.innerHTML = ''; // Clear any partially rendered previews
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const imageElement = document.createElement('img');
                imageElement.src = e.target.result;
                imageElement.classList.add('w-20', 'h-20', 'object-cover', 'rounded-lg');
                previewContainer.appendChild(imageElement);
            };
            reader.readAsDataURL(files[i]);
        }
    }

    // General input length checker (for title, short_description)
    function checkLength(element) {
        const maxLength = parseInt(element.getAttribute("maxlength"), 10);
        const currentLength = element.value.length;
        const errorMessage = document.getElementById(`${element.id}-error`);

        if (currentLength > maxLength) {
            errorMessage.style.display = "block";
            errorMessage.innerText = `Bạn đã nhập ${currentLength} ký tự. Chỉ được nhập tối đa ${maxLength} ký tự.`;
        } else if (currentLength === maxLength) {
            errorMessage.style.display = "block";
            errorMessage.innerText = `Bạn đã đạt giới hạn ${maxLength} ký tự.`;
        } else {
            errorMessage.style.display = "none";
        }
    }

    // Comprehensive validation function
    function validatePostForm() {
        // Title
        const titleInput = document.getElementById('title');
        if (titleInput.value.trim() === "") {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Tiêu đề không được để trống hoặc chỉ chứa khoảng trắng.' });
            titleInput.focus(); return false;
        }
        if (titleInput.value.length > 255) {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Tiêu đề quá dài. Tối đa 255 ký tự.' });
            titleInput.focus(); return false;
        }

        // Short Description
        const shortDescInput = document.getElementById('short_description');
        if (shortDescInput.value.trim() === "") {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Mô tả ngắn không được để trống hoặc chỉ chứa khoảng trắng.' });
            shortDescInput.focus(); return false;
        }
        if (shortDescInput.value.length > 1000) {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Mô tả ngắn quá dài. Tối đa 1000 ký tự.' });
            shortDescInput.focus(); return false;
        }

        // Category
        const parentCategory = document.getElementById('parent_category');
        const childCategory = document.getElementById('category_id');
        if (parentCategory.value === "") {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Vui lòng chọn danh mục cha.' });
            parentCategory.focus(); return false;
        }
        document.getElementById('parent_category-error').style.display = 'none';

        if (childCategory.value === "" || childCategory.disabled) {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Vui lòng chọn danh mục con hợp lệ.' });
            childCategory.focus(); return false;
        }
         document.getElementById('category_id-error').innerText = '*(Vui lòng chọn danh mục cha trước.)'; // Reset default message
         document.getElementById('category_id-error').style.display = childCategory.disabled ? 'block' : 'none';


        // Content (CKEditor)
        const editor = CKEDITOR.instances.content;
        const editorContentHtml = editor.getData();
        const editorContentPlain = editorContentHtml.replace(/<[^>]*>/g, '').trim();
        const maxHtmlLength = 3000;

        if (editorContentPlain.length === 0) {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Nội dung bài viết không được để trống.' });
            editor.focus(); return false;
        }
        if (editorContentHtml.length > maxHtmlLength) {
            Swal.fire({
                icon: 'error', title: 'Lỗi',
                text: `Nội dung bài viết (bao gồm cả định dạng) quá dài. Giới hạn là ${maxHtmlLength} ký tự HTML, bạn đang có ${editorContentHtml.length} ký tự. Vui lòng rút ngắn.`
            });
            editor.focus(); return false;
        }

        // Banner Image
        const bannerImage = document.getElementById('banner_image');
        const bannerError = document.getElementById('banner_image-error');
        if (bannerImage.files.length === 0) {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Vui lòng chọn banner ảnh.' });
            bannerImage.focus(); return false;
        }
        if (bannerImage.files[0].size > 2 * 1024 * 1024) { // 2MB
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Banner ảnh không được vượt quá 2MB.' });
            bannerImage.focus(); return false;
        }
        bannerError.style.display = 'none';


        // Gallery Images
        const galleryImages = document.getElementById('gallery_images');
        const galleryFiles = galleryImages.files;
        const galleryError = document.getElementById('gallery_images-error');

        if (galleryFiles.length < 2 || galleryFiles.length > 5) {
            const msg = "Ảnh thư viện: Bạn phải chọn ít nhất 2 ảnh và không quá 5 ảnh.";
            Swal.fire({ icon: 'error', title: 'Lỗi', text: msg });
            galleryError.innerText = msg; galleryError.style.display = 'block';
            galleryImages.focus(); return false;
        }
        for (let i = 0; i < galleryFiles.length; i++) {
            if (galleryFiles[i].size > 2 * 1024 * 1024) { // 2MB
                const msg = `Ảnh thư viện: Ảnh "${galleryFiles[i].name}" vượt quá 2MB. Mỗi ảnh phải < 2MB.`;
                Swal.fire({ icon: 'error', title: 'Lỗi', text: msg });
                galleryError.innerText = msg; galleryError.style.display = 'block';
                galleryImages.focus(); return false;
            }
        }
        galleryError.style.display = 'none';

        return true; // All good
    }

    // Form submission handling
    let isFormSubmitted = false;
    let isChanged = false;

    document.querySelectorAll('#create-post-form input, #create-post-form textarea, #create-post-form select').forEach(input => {
        input.addEventListener('input', () => isChanged = true);
        input.addEventListener('change', () => isChanged = true);
    });
    if (CKEDITOR.instances.content) {
        CKEDITOR.instances.content.on('change', () => isChanged = true);
    }


    document.getElementById("create-post-form").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default submission to handle it manually

        if (isFormSubmitted) return; // Avoid re-processing

        if (!validatePostForm()) { // Call our comprehensive validation
            return; // Stop if validation fails
        }

        // If validation passes, show confirmation
        Swal.fire({
            title: 'Bạn có chắc muốn đăng bài này không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
        }).then((result) => {
            if (result.isConfirmed) {
                // ✅ IMPORTANT: Update CKEditor content to the textarea HERE, before submitting
                if (CKEDITOR.instances.content) {
                    CKEDITOR.instances.content.updateElement();
                }

                isFormSubmitted = true;
                window.removeEventListener("beforeunload", handleBeforeUnload);
                // this.submit(); // This would re-trigger this same event listener if not careful.
                document.getElementById('create-post-form').submit(); // More direct way to submit the form
            }
        });
    });

    // Handle page unload (refresh, close tab)
    function handleBeforeUnload(e) {
        if (isChanged && !isFormSubmitted) {
            e.preventDefault();
            e.returnValue = ''; // Required for Chrome
        }
    }
    window.addEventListener("beforeunload", handleBeforeUnload);

    // Handle clicks on links or "Back" button
    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (e) {
            // Exclude links that are part of the form or have specific classes for no-warning
            if (link.closest('form') || link.classList.contains('no-unload-warning')) {
                return;
            }
            if (isChanged && !isFormSubmitted) {
                e.preventDefault();
                Swal.fire({
                    title: "Xác nhận rời trang?",
                    text: "Mọi nội dung chưa lưu sẽ bị mất nếu bạn rời khỏi trang.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Rời đi",
                    cancelButtonText: "Ở lại"
                }).then((result) => {
                    if (result.isConfirmed) {
                        isChanged = false; // Allow unload without further prompt
                        window.removeEventListener("beforeunload", handleBeforeUnload);
                        window.location.href = link.href;
                    }
                });
            }
        });
    });

    // Handle browser back button (popstate)
    // history.pushState(null, null, location.href); // Add initial state
    window.addEventListener("popstate", function (e) {
        if (isChanged && !isFormSubmitted) {
            // Prevent default back navigation until user confirms
            history.pushState(null, null, location.href); // Re-push current state to "cancel" back navigation
            Swal.fire({
                title: "Xác nhận rời trang?",
                text: "Mọi nội dung chưa lưu sẽ bị mất nếu bạn quay lại.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Rời đi",
                cancelButtonText: "Ở lại"
            }).then((result) => {
                if (result.isConfirmed) {
                    isChanged = false; // Allow unload
                    window.removeEventListener("beforeunload", handleBeforeUnload);
                    history.back(); // Perform the actual back navigation
                } else {
                    // User chose to stay, do nothing extra, history.pushState already handled it
                }
            });
        } else {
            // No changes or form already submitted, allow back navigation
            // No explicit history.back() needed here as popstate implies it's already happening
        }
    });

    // Dynamic child category filtering
    document.addEventListener('DOMContentLoaded', function () {
        const parentSelect = document.getElementById('parent_category');
        const childSelect = document.getElementById('category_id');
        const childError = document.getElementById('category_id-error');

        // Initialize CKEditor tracking if not already done
        if (CKEDITOR.instances.content && !CKEDITOR.instances.content.listenerAdded) {
             CKEDITOR.instances.content.on('change', () => isChanged = true);
             CKEDITOR.instances.content.listenerAdded = true; // custom flag
        }


        parentSelect.addEventListener('change', function () {
            const selectedParentId = this.value;
            let hasVisibleChildOptions = false;

            // Reset child select
            childSelect.value = ""; // Deselect any current child
            childSelect.disabled = !selectedParentId; // Disable if no parent selected

            // Filter and show/hide child options
            Array.from(childSelect.options).forEach(opt => {
                if (opt.value === "") { // Always show the default "-- Chọn danh mục con --"
                    opt.hidden = false;
                } else {
                    const isMatch = opt.getAttribute('data-parent') === selectedParentId;
                    opt.hidden = !isMatch;
                    if (isMatch) hasVisibleChildOptions = true;
                }
            });

            if (selectedParentId && !hasVisibleChildOptions) {
                childError.innerText = "*(Không có danh mục con cho mục cha đã chọn.)";
                childError.style.display = 'block';
                childSelect.disabled = true; // Disable if no valid children
            } else if (!selectedParentId) {
                childError.innerText = "*(Vui lòng chọn danh mục cha trước.)";
                childError.style.display = 'block';
            }
             else {
                childError.style.display = 'none';
            }
        });
         // Trigger change on load if a parent category might be pre-selected (e.g. on edit form)
        if (parentSelect.value) {
            parentSelect.dispatchEvent(new Event('change'));
        } else {
             childError.innerText = "*(Vui lòng chọn danh mục cha trước.)";
             childError.style.display = 'block'; // Show message initially if no parent selected
        }
    });
</script>
<style>
    label.required::after {
        content: " *(Bắt buộc)";
        color: red;
        font-size: smaller;
        font-weight: 200;
    }
    /* Ensure error messages are visible */
    .error-message {
        font-size: 0.875rem; /* text-sm */
    }
    #gallery_preview img, #banner_preview img {
        border: 1px solid #4A5568; /* gray-700 for dark mode, adjust if needed */
    }
</style>

</x-app-layout>
