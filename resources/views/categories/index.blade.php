<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý Danh mục') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Bỏ phần thông báo thành công cũ ở đây --}}
            
            {{-- Thông báo lỗi (giữ nguyên) --}}
            @if ($errors->any())
                <div class="bg-red-500 text-white font-bold rounded-lg px-4 py-3 mb-4 shadow-md">
                    <p class="font-semibold">Đã xảy ra lỗi:</p>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Cột Thêm mới Danh mục (giữ nguyên) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Thêm danh mục mới') }}
                            </h3>
                            {{-- BỔ SUNG: Form tạo mới đã được tích hợp và sửa lỗi --}}
                            <form id="create-category-form" method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                                @csrf

                                {{-- Tên danh mục --}}
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-300">Tên danh mục <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" required maxlength="255"
                                           value="{{ old('name') }}"
                                           class="mt-1 block w-full bg-gray-700 text-gray-200 border border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('name')
                                        <small class="text-red-500 mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Danh mục cha --}}
                                <div class="mb-4">
                                    <label for="parent_id" class="block text-sm font-medium text-gray-300">Danh mục cha</label>
                                    <select id="parent_id" name="parent_id"
                                            class="mt-1 block w-full bg-gray-700 text-gray-200 border border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">-- Không chọn (danh mục gốc) --</option>
                                        {{-- SỬA: Đổi biến cho đúng với controller --}}
                                        @foreach($parent_categories_for_form as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <small class="text-red-500 mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Mô tả --}}
                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-300">Mô tả</label>
                                    <textarea id="description" name="description" rows="4"
                                              class="mt-1 block w-full bg-gray-700 text-gray-200 border border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                                    @error('description')
                                        <small class="text-red-500 mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Ảnh banner --}}
                                <div class="mb-4">
                                    <label for="banner_image" class="block text-sm font-medium text-gray-300">Ảnh banner</label>
                                    <input type="file" id="banner_image" name="banner_image"
                                           accept="image/*"
                                           onchange="previewBannerImage(event)"
                                           class="mt-1 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-600 file:text-gray-200 hover:file:bg-gray-500">
                                    @error('banner_image')
                                        <small class="text-red-500 mt-1">{{ $message }}</small>
                                    @enderror
                                    <div id="banner_preview" class="mt-3"></div>
                                </div>

                                {{-- Nút submit --}}
                                <div class="flex items-center justify-end mt-4">
                                    <x-primary-button type="submit">
                                        {{ __('Tạo mới') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Cột Danh sách Danh mục (giữ nguyên) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                             <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Danh sách danh mục</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @forelse ($categories as $parent)
                                {{-- ... code hiển thị danh sách ... --}}
                                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <a href="{{ route('admin.posts.by_category', $parent->id) }}" class="flex items-center group">
                                                <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400 group-hover:text-indigo-700 dark:group-hover:text-indigo-300 mr-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                                <span class="font-semibold text-gray-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">{{ $parent->name }}</span>
                                            </a>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.categories.edit', $parent->id) }}" class="p-2 text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                                            </a>
                                            <button onclick="confirmDelete('{{ $parent->id }}')" class="p-2 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path></svg>
                                            </button>
                                            <form id="delete-form-{{ $parent->id }}" action="{{ route('admin.categories.destroy', $parent->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                    {{-- Danh sách con --}}
                                    @if ($parent->children->isNotEmpty())
                                        <div class="mt-3 pl-8 border-l-2 border-gray-200 dark:border-gray-700 space-y-2">
                                            @foreach ($parent->children as $child)
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <a href="{{ route('admin.posts.by_category', $child->id) }}" class="flex items-center group">
                                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-300 mr-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <span class="text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">{{ $child->name }}</span>
                                                        </a>
                                                        <span class="ml-2 text-xs bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2 py-1 rounded-full">{{ $child->posts->count() }} bài viết</span>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <a href="{{ route('admin.categories.edit', $child->id) }}" class="p-2 text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                                                        </a>
                                                        <button onclick="confirmDelete('{{ $child->id }}')" class="p-2 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path></svg>
                                                        </button>
                                                         <form id="delete-form-{{ $child->id }}" action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">Chưa có danh mục nào.</p>
                            @endforelse
                        </div>
                        
                        {{-- Phân trang --}}
                        @if ($categories->hasPages())
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                                {{ $categories->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Đoạn script này đã có sẵn trong file của bạn, chỉ cần đảm bảo nó nằm trong @push --}}
    <script>
        function confirmDelete(categoryId) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Hành động này sẽ không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Hủy',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + categoryId).submit();
                }
            })
        }
    </script>
    
    {{-- ===== SCRIPT SWEETALERT MỚI CHO THÔNG BÁO THÀNH CÔNG ===== --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: '{{ session('success') }}',
                    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827',
                    timer: 2000,
                    showConfirmButton: false,
                });
            });
        </script>
    @endif
    {{-- ========================================================== --}}
{{-- BỔ SUNG: Script để xem trước ảnh --}}
    <script>
        function previewBannerImage(event) {
            const previewContainer = document.getElementById('banner_preview');
            previewContainer.innerHTML = '';
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('h-32', 'w-auto', 'rounded-lg', 'object-cover', 'mt-2');
                    
                    const previewTitle = document.createElement('p');
                    previewTitle.textContent = 'Ảnh xem trước:';
                    previewTitle.classList.add('text-sm', 'text-gray-500', 'mb-1');
                    
                    previewContainer.appendChild(previewTitle);
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>