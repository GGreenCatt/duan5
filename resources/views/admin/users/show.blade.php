<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi tiết người dùng') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Thông tin cơ bản người dùng -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow-inner">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Thông tin cơ bản</h3>
                        <div class="space-y-3">
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">ID:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $user->id }}</span></p>
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">Tên:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $user->name }}</span></p>
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">Email:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $user->email }}</span></p>
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">Vai trò:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $user->role }}</span></p>
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">Trạng thái:</strong> 
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->status === 'active' ? 'Hoạt động' : 'Bị cấm' }}
                                </span>
                            </p>
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">Ngày tạo:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $user->created_at->format('d/m/Y H:i') }}</span></p>
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">Cập nhật cuối:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('d/m/Y H:i') }}</span></p>
                        </div>
                    </div>

                    <!-- Quản lý vai trò và hành động -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow-inner">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Quản lý vai trò & Hành động</h3>
                        @can('manage-users')
                            @if ($user->role !== 'Admin')
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Cập nhật vai trò người dùng</h4>
                                    <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center space-x-3">
                                            <select name="role" id="role" class="form-select rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 flex-grow">
                                                <option value="User" {{ $user->role === 'User' ? 'selected' : '' }}>Người dùng</option>
                                                <option value="Vip" {{ $user->role === 'Vip' ? 'selected' : '' }}>Vip</option>
                                                <option value="Editor" {{ $user->role === 'Editor' ? 'selected' : '' }}>Biên tập viên</option>
                                                <option value="Admin" {{ $user->role === 'Admin' ? 'selected' : '' }}>Quản trị</option>
                                            </select>
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Cập nhật
                                            </button>
                                        </div>
                                        @error('role')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </form>
                                </div>
                            @endif
                        @endcan

                        <div class="flex flex-wrap gap-3 mt-6">
                            @can('manage-users')
                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Chỉnh sửa thông tin
                                </a>
                            @endcan
                            <a href="{{ route('admin.users.comments', $user) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Xem lịch sử bình luận
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>