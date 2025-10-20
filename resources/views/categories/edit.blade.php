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

                    <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
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
                                    // ✨ SỬA LỖI TẠI ĐÂY: Thêm điều kiện kiểm tra hàm đã tồn tại chưa ✨
                                    if (!function_exists('renderCategoryOptions')) {
                                        function renderCategoryOptions($categories, $prefix = '', $selectedId = null, $currentId = null) {
                                            foreach ($categories as $cat) {
                                                // Không cho phép chọn chính danh mục này làm cha của nó
                                                if ($cat->id == $currentId) continue;
                                                
                                                $isSelected = ($cat->id == $selectedId) ? 'selected' : '';
                                                echo '<option value="' . $cat->id . '" ' . $isSelected . '>' . $prefix . $cat->name . '</option>';
                                                
                                                if ($cat->children->isNotEmpty()) {
                                                    // Gọi đệ quy cho các danh mục con
                                                    renderCategoryOptions($cat->children, $prefix . '—', $selectedId, $currentId);
                                                }
                                            }
                                        }
                                    }
                                    // Gọi hàm để hiển thị các lựa chọn
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
                            <label for="banner_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ảnh Banner</label>
                            <input type="file" name="banner_image" id="banner_image" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-300">
                            @if ($category->banner_image)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Ảnh hiện tại:</p>
                                    <img src="{{ asset('storage/' . $category->banner_image) }}" alt="{{ $category->name }}" class="h-20 w-auto rounded">
                                </div>
                            @endif
                            @error('banner_image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>