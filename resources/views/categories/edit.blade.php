<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chỉnh sửa Danh mục') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tên Danh mục</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Danh mục cha</label>
                            <select name="parent_id" id="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                <option value="">-- Không có --</option>
                                @php
                                    if (!function_exists('renderCategoryOptions')) {
                                        function renderCategoryOptions($categories, $prefix = '', $selectedId = null, $currentId = null) {
                                            foreach ($categories as $cat) {
                                                if ($cat->id == $currentId) continue;
                                                $isSelected = ($cat->id == $selectedId) ? 'selected' : '';
                                                echo '<option value="' . $cat->id . '" ' . $isSelected . '>' . $prefix . $cat->name . '</option>';
                                                if ($cat->children->isNotEmpty()) {
                                                    renderCategoryOptions($cat->children, $prefix . '—', $selectedId, $currentId);
                                                }
                                            }
                                        }
                                    }
                                    renderCategoryOptions($categories, '', $category->parent_id, $category->id);
                                @endphp
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mô tả</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="banner_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ảnh Banner (chọn ảnh mới để thay đổi)</label>
                            <input type="file" name="banner_image" id="banner_image" class="mt-1 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-600 file:text-gray-200 hover:file:bg-gray-500" onchange="previewBannerImage(event)">
                            
                            {{-- BỔ SUNG: Container cho ảnh xem trước --}}
                            <div id="banner_preview_container" class="mt-4">
                                @if ($category->image)
                                    <div id="current_image_container">
                                        <p class="text-sm text-gray-500 mb-2">Ảnh hiện tại:</p>
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-40 w-auto rounded-lg object-cover">
                                    </div>
                                @endif
                                {{-- Vùng xem trước ảnh mới sẽ được chèn vào đây bởi JS --}}
                                <div id="banner_preview" class="mt-3"></div>
                            </div>

                            @error('banner_image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.categories.index') }}" class="text-gray-300 hover:text-white mr-4">Hủy</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- BỔ SUNG: Script xem trước ảnh --}}
    <script>
        function previewBannerImage(event) {
            const previewContainer = document.getElementById('banner_preview');
            const currentImageContainer = document.getElementById('current_image_container');
            previewContainer.innerHTML = '';
            
            const file = event.target.files[0];
            if (file) {
                // Ẩn ảnh hiện tại khi người dùng chọn ảnh mới
                if(currentImageContainer) {
                    currentImageContainer.style.display = 'none';
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('h-40', 'w-auto', 'rounded-lg', 'object-cover');
                    
                    const previewTitle = document.createElement('p');
                    previewTitle.textContent = 'Ảnh mới xem trước:';
                    previewTitle.classList.add('text-sm', 'text-gray-500', 'mb-2');
                    
                    previewContainer.appendChild(previewTitle);
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            } else {
                // Hiện lại ảnh hiện tại nếu người dùng hủy chọn file
                if(currentImageContainer) {
                    currentImageContainer.style.display = 'block';
                }
            }
        }
    </script>
</x-app-layout>