@extends('layouts.guest_app')

@section('content') {{-- Bọc nội dung trong section content --}}
    {{-- Thêm padding top pt-8 --}}
    <div class="py-12 pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 sm:p-10 text-gray-900 dark:text-gray-100">

                    {{-- =============== TIÊU ĐỀ TRANG ĐÃ DI CHUYỂN VÀO ĐÂY =============== --}}
                    <div class="text-center mb-12">
                         {{-- Sử dụng lại H1 từ phần Hero cũ làm tiêu đề chính --}}
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                            {{ __('Về Chúng Tôi') }}
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                            Chúng tôi là cầu nối của bạn đến thế giới Công nghệ và Tài chính, mang đến những phân tích sâu sắc và tin tức cập nhật nhất.
                        </p>
                    </div>
                    {{-- =============== KẾT THÚC TIÊU ĐỀ TRANG =============== --}}


                    {{-- 2. Sứ mệnh & Tầm nhìn --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                        {{-- Sứ mệnh --}}
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                            <h3 class="text-2xl font-semibold mb-3 text-gray-800 dark:text-gray-100">Sứ mệnh</h3>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                Cung cấp cho độc giả thông tin chính xác, đa chiều và góc nhìn chuyên môn về xu hướng Công nghệ và biến động thị trường Ngân hàng - Tài chính.
                            </p>
                        </div>
                        {{-- Tầm nhìn --}}
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                            <h3 class="text-2xl font-semibold mb-3 text-gray-800 dark:text-gray-100">Tầm nhìn</h3>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                Trở thành nền tảng tin tức uy tín hàng đầu, nơi chuyên gia, nhà đầu tư và người yêu công nghệ tìm đến thông tin đáng tin cậy và cộng đồng trao đổi.
                            </p>
                        </div>
                    </div>

                    {{-- 3. Giá trị cốt lõi --}}
                    <div class="mb-16">
                        <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-gray-100">Giá trị cốt lõi</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            {{-- Giá trị 1 --}}
                            <div class="text-center p-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-full mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Chính xác</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Kiểm chứng, đáng tin cậy.</p>
                            </div>
                            {{-- Giá trị 2 --}}
                            <div class="text-center p-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-full mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Cập nhật</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Nắm bắt xu hướng.</p>
                            </div>
                            {{-- Giá trị 3 --}}
                            <div class="text-center p-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-full mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-2.377M12 6H3m9 0a3 3 0 013 3v2a3 3 0 01-3 3H3m9-6v6m0-6H9m3 6h3m-3 0H9m12 6V9a3 3 0 00-3-3H9m12 0a3 3 0 01-3 3v2a3 3 0 013 3v6m0-6V9m3 6H9m12-6H9"></path></svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Chuyên sâu</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Phân tích đa chiều.</p>
                            </div>
                            {{-- Giá trị 4 --}}
                            <div class="text-center p-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-full mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Cộng đồng</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Trao đổi tri thức.</p>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Đội ngũ của chúng tôi --}}
                    <div class="mb-12">
                        <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-gray-100">Gặp gỡ đội ngũ</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {{-- Thẻ thành viên: Nguyễn Văn Bảo Long --}}
                            <div class="text-center bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                                <img class="w-32 h-32 rounded-full mx-auto mb-4 object-cover" src="https://ui-avatars.com/api/?name=Long+Nguyen&size=128&background=7F9CF5&color=EBF4FF" alt="Nguyễn Văn Bảo Long" loading="lazy">
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Nguyễn Văn Bảo Long</h4>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Founder & Developer</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Sinh viên Công nghệ Thông tin, người đã xây dựng nền tảng này.
                                </p>
                            </div>
                            {{-- Thẻ thành viên: Placeholder 1 --}}
                            <div class="text-center bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                                <img class="w-32 h-32 rounded-full mx-auto mb-4 object-cover" src="https://ui-avatars.com/api/?name=Minh+Anh&size=128&background=6EE7B7&color=065F46" alt="Trần Thị Minh Anh" loading="lazy">
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Nguyễn Thị Quỳnh Hương</h4>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Trưởng ban Biên tập</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Đảm bảo chất lượng nội dung và định hướng phát triển chủ đề.
                                </p>
                            </div>
                            {{-- Thẻ thành viên: Placeholder 2 --}}
                            <div class="text-center bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                                <img class="w-32 h-32 rounded-full mx-auto mb-4 object-cover" src="https://ui-avatars.com/api/?name=Van+Hung&size=128&background=FDBA74&color=7C2D12" alt="Lê Văn Hùng" loading="lazy">
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Lê Mỹ Dung</h4>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Chuyên gia Tài chính</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Cung cấp các bài viết chuyên sâu về thị trường ngân hàng và đầu tư.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection 