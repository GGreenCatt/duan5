@extends('layouts.guest_app')

@section('content')
<div class="bg-white dark:bg-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl">
                Liên hệ với chúng tôi
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                Chúng tôi luôn sẵn lòng lắng nghe bạn. Vui lòng điền vào biểu mẫu dưới đây.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Thông tin liên hệ -->
            <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-2xl shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Thông tin liên hệ</h2>
                <div class="space-y-4 text-gray-700 dark:text-gray-300">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>123 Đường ABC, Quận 1, TP. Hồ Chí Minh</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <a href="mailto:info@example.com" class="hover:text-indigo-600 dark:hover:text-indigo-400">info@example.com</a>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <a href="tel:+84123456789" class="hover:text-indigo-600 dark:hover:text-indigo-400">(+84) 123 456 789</a>
                    </div>
                </div>
            </div>

            <!-- Form liên hệ -->
            <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-2xl shadow-lg">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Thành công!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('guest.contact.send') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-y-6">
                        <div>
                            <label for="full-name" class="sr-only">Họ và tên</label>
                            <input type="text" name="full-name" id="full-name" autocomplete="name" value="{{ old('full-name') }}" class="block w-full shadow-sm py-3 px-4 placeholder-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md @error('full-name') border-red-500 @enderror" placeholder="Họ và tên">
                            @error('full-name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="sr-only">Email</label>
                            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email', $email ?? '') }}" class="block w-full shadow-sm py-3 px-4 placeholder-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md @error('email') border-red-500 @enderror" placeholder="Email">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone-number" class="sr-only">Số điện thoại</label>
                            <input type="text" name="phone-number" id="phone-number" autocomplete="tel" value="{{ old('phone-number') }}" class="block w-full shadow-sm py-3 px-4 placeholder-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md @error('phone-number') border-red-500 @enderror" placeholder="Số điện thoại">
                            @error('phone-number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="subject" class="sr-only">Chủ đề</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="block w-full shadow-sm py-3 px-4 placeholder-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md @error('subject') border-red-500 @enderror" placeholder="Chủ đề">
                            @error('subject')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="message" class="sr-only">Nội dung</label>
                            <textarea id="message" name="message" rows="4" class="block w-full shadow-sm py-3 px-4 placeholder-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 border border-gray-300 rounded-md @error('message') border-red-500 @enderror" placeholder="Nội dung">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Gửi tin nhắn
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
