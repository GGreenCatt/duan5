
<!-- Custom CSS cho DataTable -->
<style>
    #posts-table_wrapper {
        color: #fff !important;
        background-color: #333 !important;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    }

    #posts-table_wrapper .dataTables_length,
    #posts-table_wrapper .dataTables_filter,
    #posts-table_wrapper .dataTables_info,
    #posts-table_wrapper .dataTables_paginate {
        color: #fff !important;
    }

    #posts-table_wrapper .dataTables_length select,
    #posts-table_wrapper .dataTables_filter input {
        background-color: #444 !important;
        color: #fff !important;
        border: 1px solid #555;
        padding: 5px;
        border-radius: 5px;
    }

    #posts-table_wrapper .dataTables_paginate a {
        color: #fff !important;
        background-color: #333 !important;
        border: 1px solid #555;
        padding: 5px 10px;
        margin: 2px;
        border-radius: 4px;
        text-decoration: none;
    }

    #posts-table_wrapper .dataTables_paginate .current {
        background-color: #007bff !important;
        color: #fff !important;
    }
.dtr-modal-content p,
.dtr-modal-content div,
.dtr-modal-content span,
.dtr-modal-content li, /* Thêm cho các thẻ danh sách */
.dtr-modal-content pre, /* Thêm cho thẻ code block */
.dtr-modal-content code,
.dtr-modal-content img, /* Thêm cho ảnh */
.dtr-modal-content table /* Thêm cho bảng */ {
    white-space: normal !important;   /* Cho phép xuống dòng */
    word-wrap: break-word !important;   /* Ngắt từ dài */
    overflow-wrap: break-word !important; /* Ngắt từ dài (chuẩn hơn) */
    max-width: 100% !important;         /* Giới hạn chiều rộng tối đa */
    height: auto !important;            /* Đảm bảo chiều cao ảnh tự điều chỉnh */
    display: block; /* Giúp max-width hoạt động tốt hơn với inline elements như span, code */
}

/* Đảm bảo code block cũng xuống dòng */
.dtr-modal-content pre {
    white-space: pre-wrap !important; /* Giữ định dạng nhưng cho phép xuống dòng */
}

/* Giới hạn chiều rộng của modal để dễ đọc hơn */
.dtr-modal div.dtr-modal-content {
    max-width: 85vw; /* Ví dụ: tối đa 85% chiều rộng màn hình */
    /* Hoặc max-width: 700px; nếu muốn cố định */
    box-sizing: border-box; /* Đảm bảo padding không làm tăng kích thước */
}
    #posts-table thead th {
        background-color: #444 !important;
        color: #fff !important;
    }

    #posts-table tbody td {
        background-color: #555 !important;
        color: #fff !important;
    }
     .excel-button-custom {
        font-size: 18px;
        background-color:#444545 !important; /* Màu xanh đậm */
        color: white !important;
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        margin-bottom: 15px;
        display: inline-block;
        transition: all 0.3s;
    }

    .excel-button-custom:hover {
        background-color: #00c853 !important; /* Màu xanh sáng hơn khi hover */
        color: #fff !important; /* Màu chữ trắng nổi bật trên nền xanh */
        transform: scale(1.05); /* Phóng to nhẹ khi hover */
    }

    /* Đảm bảo nút xuất Excel có khoảng cách dưới */
    .dt-buttons {
        margin-bottom: 15px;
    }

    /* Canh chỉnh phần top để hiện đủ các nút */
    .dataTables_wrapper .top { /* Thay đổi selector để tổng quát hơn */
        display: flex;
        flex-wrap: wrap; /* Cho phép các item xuống dòng */
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .dataTables_length {
        margin-right: 20px;
        margin-bottom: 10px; /* Thêm margin bottom cho responsive */
    }

    .dataTables_filter {
        margin-bottom: 10px; /* Thêm margin bottom cho responsive */
    }
    .dt-buttons {
        width: 100%; /* Cho nút export full width trên mobile nếu cần */
        text-align: left; /* Hoặc center tùy ý */
    }

    /* Ẩn nút sửa, xóa và xuất Excel nếu không phải admin */
    .hide-if-user {
        display: none !important;
    }

    /* CSS cho bộ lọc danh mục */
    .category-filters-container {
        display: flex;
        flex-wrap: wrap; /* Cho phép xuống dòng trên màn hình nhỏ */
        gap: 1rem; /* Khoảng cách giữa các bộ lọc */
        margin-bottom: 1rem;
    }
    .category-filters-container > div {
        display: flex;
        flex-direction: column; /* Label trên select */
    }
    .category-filters-container label {
        margin-bottom: 0.25rem;
    }
    .category-filters-container select {
        padding: 0.5rem;
        border-radius: 0.375rem; /* rounded-md */
        border: 1px solid #555;
    }


    /* Responsive adjustments */
    @media (max-width: 768px) { /* Tablet và nhỏ hơn */
        .dataTables_wrapper .top {
            flex-direction: column; /* Stack các control trên màn hình nhỏ */
            align-items: stretch; /* Các control chiếm full width */
        }
        .dataTables_length,
        .dataTables_filter,
        .dt-buttons {
            width: 100%;
            margin-right: 0;
            margin-bottom: 10px;
            text-align: left;
        }
        .dataTables_filter input {
            width: calc(100% - 10px); /* Điều chỉnh width cho input search */
        }
        .excel-button-custom {
            width: 100%;
            text-align: center;
        }
        /* Action buttons trong table */
        #posts-table .actions-cell {
            display: flex;
            flex-direction: column;
            gap: 0.5rem; /* Khoảng cách giữa các nút */
            align-items: stretch; /* Nút chiếm full width của cell */
        }
        #posts-table .actions-cell a,
        #posts-table .actions-cell button {
            width: 100%;
            text-align: center;
            margin: 0 !important; /* Reset margin nếu có */
        }

        .category-filters-container {
            flex-direction: column; /* Stack các bộ lọc danh mục */
        }
        .category-filters-container > div {
            width: 100%;
        }
    }

</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Danh sách bài đăng') }}
        </h2>
    </x-slot>

    <div class="{{ auth()->user()->role == 'User' ? 'role-user' : '' }}">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- Nút Export Excel đã được di chuyển vào DataTables buttons --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mt-8 overflow-x-auto">
                            <h3 class="text-lg font-semibold mb-4">Danh sách bài viết</h3>
                        <div class="category-filters-container mb-4">
                            <!-- Dropdown Danh mục cha -->
                            <div>
                                <label for="parent-category-filter" class="text-white mr-2">Danh mục cha:</label>
                                <select style="color: white; background-color: #333;" id="parent-category-filter" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    @foreach ($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dropdown Danh mục con -->
                            <div>
                                <label for="child-category-filter" class="text-white mr-2">Danh mục con:</label>
                                <select style="color: white; background-color: #333;"  id="child-category-filter" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    {{-- JS sẽ thêm options dựa vào danh mục cha --}}
                                </select>
                            </div>
                        </div>


                            <table id="posts-table" class="min-w-full text-sm display responsive nowrap" style="width:100%"> <!-- Thêm class display responsive nowrap -->
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Mô tả ngắn</th>
                                    <th style="display: none;" data-priority="10001" class="never content-column">Nội dung</th> <!-- data-priority thấp hơn sẽ ẩn sau, thêm class để dễ nhận biết -->
                                    <th>Danh mục</th>
                                    <th>Người đăng</th>
                                    <th>Ngày đăng</th>
                                    <th class="text-center actions-header" data-priority="1">Hành động</th> <!-- data-priority 1 để luôn hiển thị nếu có thể -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ Str::limit(strip_tags($post->short_description), 80) }}</td> {{-- Giữ strip_tags cho mô tả ngắn nếu muốn --}}
                                        <td style="display: none" class="post-content-data">{!! $post->content !!}</td> {{-- Hiển thị content gốc, DataTables sẽ xử lý --}}
                                        <td>
                                            @if($post->category)
                                                {{ $post->category->parent ? $post->category->parent->name . ' → ' : '' }}{{ $post->category->name }}
                                            @else
                                                Không có
                                            @endif
                                        </td>
                                        <td>{{ $post->user->name }}</td>
                                        <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="actions-cell text-center"> <!-- Bỏ flex, justify-center, space-x-3, dùng class mới để CSS -->
                                            <a href="{{ route('posts.show', $post) }}" class="bg-green-400 text-white px-4 py-2 rounded inline-block mb-1 md:mb-0 md:mr-1">Xem</a>
                                            <a href="{{ route('posts.edit', $post) }}" class="bg-yellow-400 text-white px-4 py-2 rounded btn-edit inline-block mb-1 md:mb-0 md:mr-1">Sửa</a>
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded btn-delete">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS ẩn nút cho role User -->
    <style>
        /* Nếu là role User thì ẩn nút Sửa và Xóa bằng visibility hidden để giữ layout */
        .role-user .btn-edit,
        .role-user .btn-delete {
            visibility: hidden;
        }

        /* Ẩn nút xuất Excel */
        .role-user .dt-buttons {
            visibility: hidden;
        }
    </style>

    <!-- DataTables và Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> <!-- Cập nhật version nếu cần -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css"> <!-- Cập nhật version -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css"> <!-- THÊM CSS CHO RESPONSIVE -->

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> <!-- Cập nhật version jquery -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> <!-- Cập nhật version -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script> <!-- THÊM JS CHO RESPONSIVE -->


    <script>

// Truyền dữ liệu danh mục con từ PHP sang JS một cách an toàn
const allChildCategories = @json($childCategories);

// Hàm tiện ích để loại bỏ thẻ HTML
function stripHtmlTags(html) {
    if (typeof html !== 'string') return '';
    return html.replace(/<\/?[^>]+(>|$)/g, "");
}

$(document).ready(function() {
    // 1. ✨ TỐI ƯU: Hàm Debounce để trì hoãn việc thực thi, giảm INP
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // 2. Khởi tạo DataTables với các tùy chọn
    const table = $('#posts-table').DataTable({
        "pageLength": 5,
        "lengthMenu": [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "Tất cả"] ],
        "language": {
            "lengthMenu": "Hiển thị _MENU_ bài viết mỗi trang",
            "zeroRecords": "Không tìm thấy bài viết nào",
            "info": "Hiển thị trang _PAGE_ trên _PAGES_",
            "infoEmpty": "Không có bài viết nào",
            "infoFiltered": "(lọc từ _MAX_ bài viết)",
            "search": "Tìm kiếm:",
            "paginate": { "first": "Đầu", "last": "Cuối", "next": "Tiếp", "previous": "Trước" },
        },
        responsive: true,
        buttons: [{
            extend: 'excelHtml5',
            text: 'Xuất Excel',
            className: 'excel-button-custom dt-button',
            action: (e, dt, node, config) => window.location.href = "{{ route('posts.export') }}"
        }],
        columnDefs: [
            { responsivePriority: 1, targets: 0 }, // Tiêu đề
            { responsivePriority: 2, targets: 5 }, // Hành động (index 5)
            { responsivePriority: 3, targets: 1 }, // Mô tả
            { responsivePriority: 4, targets: 2 }, // Danh mục (index 2)
            { responsivePriority: 5, targets: 3 }, // Người đăng
            { responsivePriority: 6, targets: 4 }, // Ngày đăng
        ],
        dom: '<"top"lfB>rt<"bottom"ip><"clear">',
    });

    // 3. Lấy các phần tử DOM cho bộ lọc
    const parentFilter = $('#parent-category-filter');
    const childFilter = $('#child-category-filter');

    // 4. Hàm lọc chính (được tối ưu để gọi qua debounce)
    function applyFilter() {
        const parentText = parentFilter.find('option:selected').text().trim();
        const childText = childFilter.find('option:selected').text().trim();
        let searchTerm = '';

        if (parentFilter.val()) { // Nếu có chọn cha
            if (childFilter.val()) { // Và có chọn con
                searchTerm = parentText + ' → ' + childText;
            } else { // Chỉ chọn cha
                searchTerm = parentText;
            }
        }
        
        // Cột Danh mục có index là 2
        table.column(2).search(searchTerm ? '^' + $.fn.dataTable.util.escapeRegex(searchTerm) : '', true, false).draw();
    }

    // 5. Gán sự kiện cho dropdown cha
    parentFilter.on('change', function() {
        const parentId = $(this).val();
        childFilter.empty().append('<option value="">-- Tất cả --</option>');

        if (parentId) {
            childFilter.prop('disabled', false);
            // Lọc và thêm các danh mục con tương ứng từ mảng phẳng
            const children = allChildCategories.filter(cat => cat.parent_id == parentId);
            children.forEach(cat => {
                childFilter.append(`<option value="${cat.id}">${cat.name}</option>`);
            });
        } else {
            childFilter.prop('disabled', true);
        }
        // ✨ TỐI ƯU INP: Gọi hàm lọc thông qua debounce với độ trễ 250ms ✨
        debounce(applyFilter, 250)();
    });

    // 6. Gán sự kiện cho dropdown con
    childFilter.on('change', debounce(applyFilter, 250));

    // 7. ✨ TỐI ƯU INP: Sử dụng Event Delegation cho nút Xóa ✨
    $('#posts-table').on('submit', '.form-delete', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Bạn có chắc muốn xóa?', text: "Hành động này không thể hoàn tác!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
            confirmButtonText: 'Có, xóa nó!', cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    
    // 8. Hiển thị thông báo thành công (nếu có)
    @if (session('success'))
        Swal.fire({
            icon: 'success', title: '{{ session('success') }}',
            showConfirmButton: false, timer: 1500
        });
    @endif
});
</script>
</script>

</x-app-layout>
