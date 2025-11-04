<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý Bài viết') }}
        </h2>
    </x-slot>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        @media (max-width: 767px) {
            .responsive-table-admin thead { display: none; }
            .responsive-table-admin tr {
                display: block;
                margin-bottom: 1rem;
                border-radius: 0.5rem;
                overflow: hidden;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                border: 1px solid #4a5568; /* dark:border-gray-700 */
            }
            .responsive-table-admin td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 1rem;
                text-align: right;
                border-bottom: 1px solid #4a5568; /* dark:border-gray-700 */
            }
            .responsive-table-admin td:last-child { border-bottom: none; }
            .responsive-table-admin td::before {
                content: attr(data-label);
                font-weight: 600;
                text-align: left;
                margin-right: 1rem;
                color: #a0aec0; /* dark:text-gray-400 */
            }
            .responsive-table-admin .actions-cell {
                justify-content: center;
            }
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng số bài viết</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalPosts ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Bài viết tháng này</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $postsThisMonth ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-3 md:space-y-0">
                        <form id="filter-form" method="GET" action="{{ route('admin.posts.list') }}" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <select name="parent_category_id" id="parent_category_id" class="bg-gray-900 border-gray-700 rounded-md shadow-sm text-white text-sm">
                                <option value="">-- Lọc theo danh mục cha --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ request('parent_category_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <select name="child_category_id" id="child_category_id" class="bg-gray-900 border-gray-700 rounded-md shadow-sm text-white text-sm">
                                <option value="">-- Lọc theo danh mục con --</option>
                                {{-- Options will be populated by JS --}}
                            </select>

                            <input type="search" name="search" placeholder="Tìm theo tiêu đề..." value="{{ request('search') }}" class="bg-gray-900 border-gray-700 rounded-md shadow-sm text-white text-sm">
                            
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md">Lọc</button>
                        </form>

                        <div class="flex space-x-2 w-full md:w-auto">
                             <a href="{{ route('admin.posts.create') }}" class="w-full md:w-auto text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">Thêm mới</a>
                             <a href="{{ route('admin.posts.export') }}" class="w-full md:w-auto text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">Xuất Excel</a>
                        </div>
                    </div>
                    
                   {{-- ===== FORM CHO HÀNH ĐỘNG HÀNG LOẠT (ĐÃ CẬP NHẬT) ===== --}}
                    <form id="bulk-action-form" action="{{ route('admin.posts.bulkDestroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        {{-- JavaScript sẽ điền các input ẩn vào đây --}}
                    </form>
                    {{-- ======================================================== --}}

                    <div class="mb-4">
                        <button id="bulk-delete-btn" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md opacity-50 cursor-not-allowed" disabled>
                            Xóa các mục đã chọn
                        </button>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 responsive-table-admin">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col" class="p-4"><input type="checkbox" id="select-all-checkbox" class="rounded bg-gray-900 border-gray-600 text-indigo-600 focus:ring-indigo-500"></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tiêu đề</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tác giả</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Danh mục</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ngày tạo</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($posts as $post)
                                    <tr>
                                        <td data-label="Chọn" class="p-4"><input type="checkbox" name="post_ids[]" value="{{ $post->id }}" class="post-checkbox rounded bg-gray-900 border-gray-600 text-indigo-600 focus:ring-indigo-500"></td>
                                        <td data-label="Tiêu đề" class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Str::limit($post->title, 40) }}</div><div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $post->id }}</div></td>
                                        <td data-label="Tác giả" class="px-6 py-4 whitespace-nowrap"><div class="flex items-center"><div class="flex-shrink-0 h-8 w-8"><img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'A') }}&color=7F9CF5&background=EBF4FF" alt=""></div><div class="ml-3"><div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $post->user->name ?? 'N/A' }}</div></div></div></td>
                                        <td data-label="Danh mục" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $post->category->name ?? 'N/A' }}</td>
                                        <td data-label="Ngày tạo" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->format('d/m/Y') }}</td>
                                        <td data-label="Hành động" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium actions-cell">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('admin.posts.show_for_admin', $post->id) }}" class="p-2 text-blue-500 bg-blue-500/10 hover:bg-blue-500/20 rounded-full" title="Xem"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="p-2 text-indigo-500 bg-indigo-500/10 hover:bg-indigo-500/20 rounded-full" title="Sửa"><i class="fas fa-edit"></i></a>
                                                <a href="#" data-form-id="delete-form-{{ $post->id }}" class="p-2 text-red-500 bg-red-500/10 hover:bg-red-500/20 rounded-full btn-delete" title="Xóa"><i class="fas fa-trash"></i></a>
                                            </div>
                                            <form id="delete-form-{{ $post->id }}" action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Không tìm thấy bài viết nào.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($posts->hasPages())
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const parentCategoriesData = @json($parentCategories->keyBy('id'));
            const parentSelect = document.getElementById('parent_category_id');
            const childSelect = document.getElementById('child_category_id');
            
            function updateChildDropdown() {
                const parentId = parentSelect.value;
                const currentChildId = "{{ request('child_category_id') }}";

                childSelect.innerHTML = '<option value="">-- Lọc theo danh mục con --</option>';

                if (parentId && parentCategoriesData[parentId] && parentCategoriesData[parentId].children.length > 0) {
                    childSelect.disabled = false;
                    
                    parentCategoriesData[parentId].children.forEach(child => {
                        const option = new Option(child.name, child.id);
                        if (child.id == currentChildId) {
                            option.selected = true;
                        }
                        childSelect.add(option);
                    });
                } else {
                    childSelect.disabled = true;
                }
            }

            parentSelect.addEventListener('change', updateChildDropdown);
            updateChildDropdown();

            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const formId = this.dataset.formId;
                    const form = document.getElementById(formId);
                    if(form){
                        Swal.fire({
                            title: 'Bạn có chắc muốn xóa?', text: "Hành động này sẽ không thể hoàn tác!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Vâng, xóa nó!', cancelButtonText: 'Hủy', background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff', color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827'
                        }).then((result) => { if (result.isConfirmed) { form.submit(); } });
                    }
                });
            });

            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const postCheckboxes = document.querySelectorAll('.post-checkbox');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            
            function updateBulkDeleteButtonState() {
                const selectedCount = document.querySelectorAll('.post-checkbox:checked').length;
                if (selectedCount > 0) {
                    bulkDeleteBtn.disabled = false;
                    bulkDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    bulkDeleteBtn.disabled = true;
                    bulkDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            selectAllCheckbox.addEventListener('change', function () {
                postCheckboxes.forEach(checkbox => { checkbox.checked = this.checked; });
                updateBulkDeleteButtonState();
            });

            postCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    if (!checkbox.checked) {
                        selectAllCheckbox.checked = false;
                    } else if (document.querySelectorAll('.post-checkbox:checked').length === postCheckboxes.length) {
                        selectAllCheckbox.checked = true;
                    }
                    updateBulkDeleteButtonState();
                });
            });

            bulkDeleteBtn.addEventListener('click', function() {
                const selectedCheckboxes = document.querySelectorAll('.post-checkbox:checked');
                const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
                
                if (selectedIds.length > 0) {
                     Swal.fire({
                        title: `Bạn có chắc muốn xóa ${selectedIds.length} bài viết?`,
                        text: "Hành động này sẽ không thể hoàn tác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Vâng, xóa tất cả!',
                        cancelButtonText: 'Hủy',
                        background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Lấy form
                            const form = document.getElementById('bulk-action-form');
                            // Xóa các input cũ (nếu có)
                            form.innerHTML = '';
                            // Thêm CSRF và Method
                            form.insertAdjacentHTML('beforeend', `@csrf @method('DELETE')`);
                            // Thêm các ID được chọn vào form
                            selectedIds.forEach(id => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'ids[]';
                                input.value = id;
                                form.appendChild(input);
                            });
                            // Submit form
                            form.submit();
                        }
                    });
                }
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success', title: 'Thành công!', text: '{{ session('success') }}', background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff', color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827', timer: 2000, showConfirmButton: false,
                });
            @endif
        });
    </script>
</x-app-layout>