<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tạo danh mục mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <script>
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

                    <form id="create-category-form" method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Tên danh mục --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-white">Tên danh mục <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" required maxlength="255"
                                   value="{{ old('name') }}"
                                   class="mt-1 block w-full bg-gray-800 text-white border border-gray-600 rounded-md shadow-sm">
                            @error('name')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Danh mục cha --}}
                        <div class="mb-4">
                            <label for="parent_category" class="block text-sm font-medium text-white">Danh mục cha</label>
                            <select id="parent_category" name="parent_id"
                                    class="mt-1 block w-full bg-gray-800 text-white border border-gray-600 rounded-md shadow-sm">
                                <option value="">-- Không chọn (tạo danh mục cha) --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Mô tả --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-white">Mô tả</label>
                            <textarea id="description" name="description" rows="5"
                                      class="mt-1 block w-full bg-gray-800 text-white border border-gray-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Ảnh banner --}}
                        <div class="mb-4">
                            <label for="banner_image" class="block text-sm font-medium text-white">Ảnh banner</label>
                            <input type="file" id="banner_image" name="banner_image"
                                   accept="image/*"
                                   onchange="previewBannerImage(event)"
                                   class="mt-1 block w-full bg-gray-800 text-white border border-gray-600 rounded-md shadow-sm">
                            @error('banner_image')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                            <div id="banner_preview" class="mt-2"></div>
                        </div>

                        {{-- Nút submit --}}
                        <x-primary-button type="submit">Tạo danh mục</x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Script preview ảnh --}}
    <script>
        function previewBannerImage(event) {
            const preview = document.getElementById('banner_preview');
            preview.innerHTML = '';
            const file = event.target.files[0];
            if (file) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '200px';
                img.style.borderRadius = '8px';
                preview.appendChild(img);
            }
        }
    </script>
</x-app-layout>
