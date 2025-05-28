<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý danh mục') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700">
                + Thêm danh mục
            </a>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 flex space-x-6 items-center category-filters-container">
                    <!-- Dropdown Danh mục cha -->
                    <div>
                        <label for="parent-category-filter" class="text-gray-700 dark:text-gray-300 font-medium mr-2">Danh mục cha:</label>
                        <select id="parent-category-filter" class="form-select dark:bg-gray-700 dark:text-white border rounded px-3 py-1">
                            <option value="">-- Tất cả --</option>
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown Danh mục con -->
                    <div>
                        <label for="child-category-filter" class="text-gray-700 dark:text-gray-300 font-medium mr-2">Danh mục con:</label>
                        <select id="child-category-filter" class="form-select dark:bg-gray-700 dark:text-white border rounded px-3 py-1">
                            <option value="">-- Tất cả --</option>
                            {{-- JS sẽ thêm options dựa vào danh mục cha --}}
                        </select>
                    </div>
                </div>

                <table id="categories-table" class="min-w-full text-sm table-auto border-collapse border border-gray-200 dark:border-gray-600 rounded" style="width:100%">
                    <thead class="bg-gray-100 dark:bg-gray-700">
    <tr>
        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-white">ID</th>
        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-white">Tên danh mục</th>
        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-white">Danh mục cha</th>
        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-white">Mô tả</th>
        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-white">Người tạo</th>
        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-white">Banner</th>
        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center text-white">Hành động</th>
    </tr>
</thead>
<tbody>
    @foreach($categories as $cat)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-white">{{ $cat->id }}</td>
            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-white">{{ $cat->name }}</td>
            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-white">
                {{ $cat->parent?->name ?? '---' }}
            </td>
            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-white">{{ $cat->description }}</td>
            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-white">{{ $cat->author }}</td>
            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                @if($cat->image)
                    <img src="{{ asset('storage/' . $cat->image) }}" alt="Banner {{ $cat->name }}" style="max-width: 120px; max-height: 80px; object-fit: contain; border-radius: 6px;">
                @else
                    <span class="text-white">---</span>
                @endif
            </td>
            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">
                <a href="{{ route('posts.listdanhsach', ['category_id' => $cat->id]) }}" class="bg-green-500 text-black px-3 py-2 rounded hover:bg-green-600 mr-2" title="Xem bài viết">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('categories.edit', $cat->id) }}" class="bg-yellow-500 text-black px-3 py-2 rounded hover:bg-yellow-500 hover:text-black mr-2" title="Sửa danh mục">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" style="display:inline;" class="delete-category-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-black px-3 py-2 rounded hover:bg-red-600" title="Xoá danh mục">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

                </table>

                {{-- Nếu cần phân trang, thêm ở đây --}}
                {{-- {{ $categories->links() }} --}}
            </div>
        </div>
    </div>

    {{-- SweetAlert2 CDN - Nên đặt trong layout chính nếu sử dụng ở nhiều nơi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- JS để xử lý dropdown cha-con lọc danh mục --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const parentSelect = document.getElementById('parent-category-filter');
            const childSelect = document.getElementById('child-category-filter');
            const categories = @json($categories);

            function filterChildOptions(parentId) {
                // Xóa options hiện tại (trừ option đầu)
                childSelect.innerHTML = '<option value="">-- Tất cả --</option>';

                if(!parentId) return;

                // Lọc các danh mục con có parent_id = parentId
                const childCats = categories.filter(cat => cat.parent_id == parentId);

                childCats.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.id;
                    opt.textContent = cat.name;
                    childSelect.appendChild(opt);
                });
            }

            parentSelect.addEventListener('change', function() {
                filterChildOptions(this.value);
            });

            // Hiển thị thông báo session bằng SweetAlert2
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: @json(session('success')),
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: @json(session('error')),
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            // Xử lý xác nhận xóa bằng SweetAlert2
            const deleteForms = document.querySelectorAll('.delete-category-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Ngăn form submit ngay lập tức
                    const currentForm = this;

                    Swal.fire({
                        title: 'Xác nhận xóa',
                        text: "Bạn có chắc chắn muốn xóa danh mục này? Hành động này không thể hoàn tác.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Đồng ý, Xóa!',
                        cancelButtonText: 'Hủy bỏ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            currentForm.submit(); // Submit form nếu người dùng xác nhận
                        }
                    });
                });
            });
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</x-app-layout>
