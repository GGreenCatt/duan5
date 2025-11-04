<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi tiết liên hệ') }}: {{ $contact->subject }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Thông tin liên hệ -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow-inner">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Thông tin người gửi</h3>
                        <div class="space-y-3">
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">ID:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $contact->id }}</span></p>
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">Tên:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $contact->name }}</span></p>
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">Email:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $contact->email }}</span></p>
                            <p class="flex justify-between items-center"><strong class="text-gray-600 dark:text-gray-300">Ngày gửi:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $contact->created_at->format('d/m/Y H:i') }}</span></p>
                        </div>
                    </div>

                    <!-- Nội dung tin nhắn -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow-inner">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Nội dung tin nhắn</h3>
                        <div class="space-y-3">
                            <p><strong class="text-gray-600 dark:text-gray-300">Chủ đề:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $contact->subject }}</span></p>
                            <p><strong class="text-gray-600 dark:text-gray-300">Tin nhắn:</strong></p>
                            <div class="bg-white dark:bg-gray-900 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $contact->message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('admin.contacts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>