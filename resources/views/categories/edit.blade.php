<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sửa danh mục') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Tên danh mục --}}
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Tên danh mục</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    {{-- Danh mục cha --}}
                    <div class="mb-4">
                        <label for="parent_id" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Danh mục cha</label>
                        <select name="parent_id" id="parent_id"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">-- Không có danh mục cha (Là danh mục gốc) --</option>
                            @php
                                // Hàm đệ quy để hiển thị danh mục và các con của nó
                                function renderCategoryOptions($categories, $currentCategory, $selectedParentId, $prefix = '') {
                                    foreach ($categories as $cat) {
                                        // Không cho phép chọn chính nó hoặc con của nó làm cha
                                        if ($cat->id === $currentCategory->id) continue;

                                        echo '<option value="' . $cat->id . '"' . ($selectedParentId == $cat->id ? ' selected' : '') . '>';
                                        echo $prefix . $cat->name;
                                        echo '</option>';

                                        // Không cần hiển thị con của con trong dropdown này, vì chúng ta chỉ chọn cha trực tiếp
                                    }
                                }
                                renderCategoryOptions($parentCategories, $category, old('parent_id', $category->parent_id));
                            @endphp
                        </select>
                    </div>

                    {{-- Mô tả --}}
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Mô tả</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('description', $category->description) }}</textarea>
                    </div>

                    {{-- Tên tác giả --}}
                    <div class="mb-4">
                        <label for="author" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Người tạo/Tác giả</label>
                        <input type="text" name="author" id="author" value="{{ old('author', $category->author) }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    {{-- Ảnh banner --}}
                    <div class="mb-6"> {{-- Tăng margin bottom --}}
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Banner hiện tại</label>
                        @if($category->image)
                            <div class="flex items-center space-x-4 mb-3"> {{-- Tăng margin bottom --}}
                                <img src="{{ asset('storage/' . $category->image) }}" alt="Banner {{ $category->name }}"
                                    style="max-width: 200px; max-height: 120px; object-fit: contain; border-radius: 0.375rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">

                                {{-- Nút xóa ảnh --}}
                                <button type="button" onclick="confirmDeleteImage()" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-md shadow-sm text-sm transition ease-in-out duration-150">
                                    Xoá ảnh
                                </button>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 italic mb-3">Chưa có ảnh banner.</p>
                        @endif

                        <label for="image" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Tải lên / Cập nhật ảnh banner</label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-gray-600 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-gray-500">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Để trống nếu không muốn thay đổi banner.</p>
                    </div>

                    <div class="flex items-center justify-start space-x-4 mt-6"> {{-- Tăng margin top --}}
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-md shadow-sm transition ease-in-out duration-150">
                            Cập nhật danh mục
                        </button>
                        <a href="{{ route('categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200 font-semibold px-6 py-2 rounded-md shadow-sm transition ease-in-out duration-150">
                                @endif
                            @endforeach
                        </select>
                    </div>
                </form>
                {{-- Form ẩn để xóa ảnh, kích hoạt bằng JavaScript --}}
                <form id="deleteImageForm" action="{{ route('categories.deleteImage', $category->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDeleteImage() {
            Swal.fire({
                title: 'Xác nhận xóa ảnh',
                text: "Bạn có chắc chắn muốn xóa ảnh banner này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Đồng ý, Xóa!',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteImageForm').submit();
                }
            });
        }
    </script>
</x-app-layout>
