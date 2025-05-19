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
                        <div id="success-message" class="bg-green-500 text-white p-4 mb-4 rounded-lg flex justify-between items-center">
                            <span>{{ session('success') }}</span>
                            <button onclick="document.getElementById('success-message').remove()" class="ml-4 font-bold">✖</button>
                        </div>
                    @endif

                    <form id="create-post-form" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" onsubmit="return validatePostForm(event)">
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
                            <label for="content" class="block text-sm font-medium text-white required">Nội dung</label>
                            <textarea id="content" name="content" rows="10" class="mt-1 block w-full bg-gray-800 text-white" required maxlength="3000" oninput="checkLength(this)"></textarea>
                            <small class="text-red-500 error-message" id="content-error" style="display: none;"></small>
                        </div>

                        <div class="mb-4">
                            <label for="banner_image" class="block text-sm font-medium text-white required">Banner ảnh</label>
                            <input required type="file" id="banner_image" name="banner_image" class="mt-1 block w-full bg-gray-800 text-white" onchange="previewBannerImage(event)">
                            <small class="text-red-500 error-message" id="banner_image-error" style="display: none;"></small>
                            <div id="banner_preview" class="mt-2"></div>
                        </div>

                        <div class="mb-4">
                            <label for="gallery_images" class="block text-sm font-medium text-white required">Ảnh thư viện</label>
                            <input required type="file" id="gallery_images" name="gallery_images[]" class="mt-1 block w-full bg-gray-800 text-white" multiple onchange="previewGalleryImages(event)">
                            <small class="text-red-500 error-message" id="gallery_images-error" style="display: none;"></small>
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
    // Preview multiple gallery images
    function previewGalleryImages(event) {
        const previewContainer = document.getElementById('gallery_preview');
        previewContainer.innerHTML = ''; // Clear previous previews
        const files = event.target.files;

        // Kiểm tra số lượng ảnh và dung lượng
        if (files.length < 2 || files.length > 5) {
            alert("Bạn phải chọn ít nhất 2 ảnh và không quá 5 ảnh.");
            event.target.value = ""; // Xóa lựa chọn
            return;
        }

        for (let i = 0; i < files.length; i++) {
            if (files[i].size > 2 * 1024 * 1024) { // 2MB
                alert("Mỗi ảnh phải có kích thước không quá 2MB.");
                event.target.value = ""; // Xóa lựa chọn
                return;
            }

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

    // Ngăn gửi form nếu không đủ ảnh hoặc ảnh quá lớn
    document.querySelector("form").addEventListener("submit", function(event) {
        const galleryImages = document.getElementById('gallery_images').files;

        if (galleryImages.length < 2 || galleryImages.length > 5) {
            alert("Bạn phải chọn ít nhất 2 ảnh và không quá 5 ảnh.");
            event.preventDefault();
            return;
        }

        for (let i = 0; i < galleryImages.length; i++) {
            if (galleryImages[i].size > 2 * 1024 * 1024) { // 2MB
                alert("Mỗi ảnh phải có kích thước không quá 2MB.");
                event.preventDefault();
                return;
            }
        }
    });

</script>
<script>
    function validatePostForm(event) {
        // Lấy tất cả input và textarea trong form
        const inputs = document.querySelectorAll("#create-post-form input[type='text'], #create-post-form textarea");
        for (const input of inputs) {
            if (input.value.trim() === "") {
                alert("Không được nhập chỉ khoảng trắng hoặc bỏ trống các trường bắt buộc.");
                input.focus();
                return false;
            }
        }
    }


    // Kiểm tra giới hạn ký tự và hiển thị thông báo
    function checkLength(element) {
        const maxLength = element.getAttribute("maxlength");
        const errorMessage = document.getElementById(`${element.id}-error`);
        if (element.value.length >= maxLength) {
            errorMessage.style.display = "block";
            errorMessage.innerText = `Bạn đã nhập tối đa ${maxLength} ký tự.`;
        } else {
            errorMessage.style.display = "none";
        }
    }

</script>
<style>
    label.required::after {
        content: " *(Bắt buộc)";
        color: red;
        font-weight: bold;
    }
</style>

</x-app-layout>
