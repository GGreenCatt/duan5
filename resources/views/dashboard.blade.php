<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Danh sách bài đăng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Nút thêm bài đăng -->
                    <div class="mb-4">
                        <a href="{{ route('posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Thêm bài đăng</a>
                    </div>

                    <!-- Danh sách bài đăng -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold">Bài viết gần đây</h3>
                        <table id="posts-table" class="table-auto w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Chủ đề</th>
                                    <th class="px-4 py-2">Người đăng</th>
                                    <th class="px-4 py-2">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($posts as $post)
                                    <tr>
                                        <td class="px-4 py-2">{{ $post->title }}</td>
                                        <td class="px-4 py-2">{{ $post->user->name }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('posts.show', $post) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Xem</a>
                                            <a href="{{ route('posts.edit', $post) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">Sửa</a>
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-center">Không có bài viết nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thêm các script cần thiết cho DataTable -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#posts-table').DataTable();
        });
    </script>
</x-app-layout>
