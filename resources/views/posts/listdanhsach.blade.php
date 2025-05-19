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
       .dt-buttons {
        margin-bottom: 15px;
    }

    /* Canh chỉnh phần top để hiện đủ các nút */
    .dataTables_length {
        display: inline-block;
        margin-right: 20px;
    }

    .dataTables_filter {
        display: inline-block;
        float: right;
    }
        /* Ẩn nút sửa, xóa và xuất Excel nếu không phải admin */
    .hide-if-user {
        display: none !important;
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
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if (session('success'))
                            <div id="success-message" class="bg-green-500 text-white p-4 mb-4 rounded-lg flex justify-between items-center">
                                <span>{{ session('success') }}</span>
                                <button onclick="document.getElementById('success-message').remove()" class="ml-4 font-bold">✖</button>
                            </div>
                        @endif

                        <div class="mt-8 overflow-x-auto">
                            <h3 class="text-lg font-semibold mb-4">Danh sách bài viết</h3>
                            <table id="posts-table" class="min-w-full text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th>Chủ đề</th>
                                    <th>Mô tả ngắn</th>
                                    <th style="display: none;">Nội dung</th> <!-- Thêm cột Nội dung -->
                                    <th>Người đăng</th>
                                    <th>Ngày đăng</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ Str::limit($post->short_description, 80) }}</td>
                                        <td style="display: none">{{ Str::limit(strip_tags($post->content), 100) }}</td> <!-- Thêm nội dung bài viết -->
                                        <td>{{ $post->user->name }}</td>
                                        <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="flex justify-center space-x-3">
                                            <a href="{{ route('posts.show', $post) }}" class="bg-green-400 text-white px-4 py-2 rounded">Xem</a>
                                            <a href="{{ route('posts.edit', $post) }}" class="bg-yellow-400 text-white px-4 py-2 rounded btn-edit">Sửa</a>
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');" class="inline-block form-delete">
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

    <script>
        function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

function decodeHtmlMultipleTimes(html, times = 2) {
    let result = html;
    for(let i = 0; i < times; i++) {
        result = decodeHtml(result);
    }
    return result;
}
    $(document).ready(function() {
        $('#posts-table').DataTable({
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
                }
            },
            dom: '<"top"lfB>rt<"bottom"ip><"clear">',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '⬇️ Xuất Excel',
                    className: 'excel-button-custom',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
                        format: {
                            body: function(data, row, column, node) {
                                let tmp = data.replace(/<\/?[^>]+(>|$)/g, "");
                                let decoded = decodeHtmlMultipleTimes(tmp, 2);
                                return decoded;
                            }
                        }
                    }
                }
            ]
        });
    });
    </script>

</x-app-layout>

