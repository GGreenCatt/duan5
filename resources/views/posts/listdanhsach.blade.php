
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
                                    <th style="display: none;" data-priority="10001" class="content-column">Nội dung</th> <!-- data-priority thấp hơn sẽ ẩn sau, thêm class để dễ nhận biết -->
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
        // Hàm tiện ích để loại bỏ thẻ HTML, dùng cho DataTables render
        function stripHtmlTags(html) {
            if (typeof html !== 'string') return '';
            return html.replace(/<\/?[^>]+(>|$)/g, "");
        }

    $(document).ready(function() {
        const table = $('#posts-table').DataTable({
            "pageLength": 5,
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bài viết mỗi trang",
                "zeroRecords": "Không tìm thấy bài viết nào",
                "info": "Hiển thị trang _PAGE_ trên _PAGES_",
                "infoEmpty": "Không có bài viết nào",
                "infoFiltered": "(lọc từ _MAX_ bài viết)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Tiếp",
                    "previous": "Trước"
                },
                "responsive": { // Ngôn ngữ cho nút responsive (nếu cần)
                    "details": {
                        "display": $.fn.dataTable.Responsive.display.modal( {
                            header: function ( row ) {
                                var data = row.data();
                                return 'Chi tiết cho '+data[0]; // Giả sử cột đầu là tiêu đề
                            }
                        } ),
                        "renderer": $.fn.dataTable.Responsive.renderer.tableAll( {
                            tableClass: 'table' // class cho bảng con
                        } )
                    }
                }
            },
            responsive: true, // KÍCH HOẠT RESPONSIVE
            buttons: [ // Cấu hình các nút, bao gồm nút Excel
                {
                    extend: 'excelHtml5',
                    text: 'Xuất Excel',
                    className: 'excel-button-custom dt-button', // Thêm class tùy chỉnh nếu cần
                    action: function ( e, dt, node, config ) {
                        // Chuyển hướng đến route export của bạn
                        window.location.href = "{{ route('posts.export') }}";
                    }
                }
            ],
            columnDefs: [ // Có thể định nghĩa độ ưu tiên cho cột nào ẩn trước
                { responsivePriority: 1, targets: 0 }, // Chủ đề
                { responsivePriority: 2, targets: 6 }, // Hành động
                { responsivePriority: 3, targets: 1 }, // Mô tả
                { responsivePriority: 4, targets: 3 }, // Danh mục
                { responsivePriority: 5, targets: 4 }, // Người đăng
                { responsivePriority: 6, targets: 5 }, // Ngày đăng
                {
                    responsivePriority: 10001,
                    targets: 2, // Index của cột "Nội dung"
                    render: function ( data, type, row ) {
                        // Khi hiển thị trong bảng (display) hoặc lọc (filter), loại bỏ HTML và giới hạn ký tự.
                        // Cho các mục đích khác (như sort, type, hoặc quan trọng là khi EXPORT), trả về dữ liệu gốc.
                        return (type === 'display' || type === 'filter') ? stripHtmlTags(data).substring(0, 100) + '...' : data;
                    }
                }
            ],
            dom: '<"top"lfB>rt<"bottom"ip><"clear">', // 'B' là Buttons, 'l' là length, 'f' là filter
        });

        // Cập nhật danh mục con khi chọn danh mục cha
        $('#parent-category-filter').on('change', function() {
            const parentId = $(this).val();
            const childSelect = $('#child-category-filter');
            childSelect.empty().append('<option value="">-- Tất cả --</option>');

            if (parentId !== "") {
                const filteredChildren = allChildCategories.filter(cat => cat.parent_id == parentId);
                filteredChildren.forEach(cat => {
                    childSelect.append(`<option value="${cat.id}">${cat.name}</option>`);
                });
            }

            // Kích hoạt lại lọc
            $('#child-category-filter').val('');
            filterPosts();
        });

        // Khi chọn danh mục con
        $('#child-category-filter').on('change', function() {
            filterPosts();
        });

        // Hàm lọc bài viết
        function filterPosts() {
            const parentId = $('#parent-category-filter').val();
            const childId = $('#child-category-filter').val();
            let searchTerm = '';

            const parentCategory = $('#parent-category-filter option:selected').text();
            const childCategory = $('#child-category-filter option:selected').text();

            table.column(3).search('').draw(); // Reset trước

            if (childId && childId !== "" && childCategory !== "-- Tất cả --") {
                // Tìm chính xác theo "Cha > Con"
                searchTerm = parentCategory.trim() + ' → ' + childCategory.trim();
                table.column(3).search('^' + $.fn.dataTable.util.escapeRegex(searchTerm) + '$', true, false).draw();
            } else if (parentId && parentId !== "" && parentCategory !== "-- Tất cả --") {
                // Tìm tất cả bài viết có danh mục cha là...
                // Chúng ta sẽ tìm kiếm tất cả các dòng BẮT ĐẦU BẰNG tên danh mục cha.
                const parentNameEscaped = $.fn.dataTable.util.escapeRegex(parentCategory.trim());
                searchTerm = `^${parentNameEscaped}`; // Tìm các dòng bắt đầu bằng tên danh mục cha
                table.column(3).search(searchTerm, true, false).draw();
            } else {
                table.column(3).search('').draw(); // reset nếu không có gì
            }
        }
    });
    // Tự động lọc khi trang tải nếu có category_id trong URL
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const categoryIdFromUrl = urlParams.get('category_id');

        if (categoryIdFromUrl) {
            // Tìm xem categoryIdFromUrl là cha hay con
            const isParent = $('#parent-category-filter option[value="' + categoryIdFromUrl + '"]').length > 0;
            const isChild = allChildCategories.some(cat => cat.id == categoryIdFromUrl);

            if (isChild) {
                const childCategory = allChildCategories.find(cat => cat.id == categoryIdFromUrl);
                if (childCategory && childCategory.parent_id) {
                    $('#parent-category-filter').val(childCategory.parent_id).trigger('change'); // Chọn cha và trigger change để load con
                    // Đợi một chút để danh sách con được load rồi mới chọn
                    setTimeout(() => {
                        $('#child-category-filter').val(categoryIdFromUrl).trigger('change'); // Chọn con và trigger change để lọc
                    }, 100); // 100ms có thể cần điều chỉnh
                }
            } else if (isParent) {
                $('#parent-category-filter').val(categoryIdFromUrl).trigger('change'); // Chọn cha và trigger change để lọc
            }
        }
    });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.form-delete').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Ngăn submit ngay

                Swal.fire({
                    title: 'Bạn có chắc muốn xóa?',
                    text: "Hành động này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Có, xóa nó!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Tiếp tục submit nếu xác nhận
                    }
                });
            });
        });
        window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Trang load lại từ cache, không hiện thông báo
        return;
    }
    @if (session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 1500
    });
    @endif
});
    </script>
<script>
    const allChildCategories = @json($childCategories);
</script>

</x-app-layout>
