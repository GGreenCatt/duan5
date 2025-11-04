            {{-- =============== FOOTER =============== --}}
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        
                        {{-- Cột 1: Giới thiệu & Mạng xã hội --}}
                        <div>
                            <div class="flex items-center">
                                {{-- Link logo trỏ về trang chủ khách --}}
                                <a href="{{ route('guest.home') }}"> {{-- Sửa user.dashboard thành guest.home --}}
                                    <x-application-logo class="block h-10 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                </a>
                                <span class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-100">{{ config('app.name', 'Laravel') }}</span>
                            </div>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                Trang tin tức và bài viết hàng đầu về công nghệ, tài chính và cuộc sống.
                            </p>
                            <div class="flex space-x-5 mt-5">
                                {{-- Icons mạng xã hội --}}
                                <a href="#" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" /></svg>
                                </a>
                            </div>
                        </div>

                        {{-- Cột 2: Điều hướng --}}
                        <div>
                            <h5 class="font-semibold text-gray-800 dark:text-gray-100 uppercase tracking-wider">Điều hướng</h5>
                            <ul class="mt-4 space-y-3">
                                {{-- ===== DÒNG CẦN SỬA ===== --}}
                                <li><a href="{{ route('guest.home') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Trang chủ</a></li> {{-- Sửa user.dashboard thành guest.home --}}
                                {{-- ========================= --}}
                                <li><a href="{{ route('about') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Về chúng tôi</a></li>
                                <li><a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Liên hệ</a></li>
                                <li><a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Chính sách bảo mật</a></li>
                            </ul>
                        </div>

                        {{-- Cột 3: Danh mục chính --}}
                        <div>
                            <h5 class="font-semibold text-gray-800 dark:text-gray-100 uppercase tracking-wider">Chủ đề chính</h5>
                            <ul class="mt-4 space-y-3">
                                {{-- Chú ý: Cập nhật các link này nếu bạn có route lọc danh mục --}}
                                <li><a href="{{ route('guest.posts.by_category', ['category' => 'cong-nghe']) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Công nghệ</a></li>
                                <li><a href="{{ route('guest.posts.by_category', ['category' => 'ngan-hang']) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Ngân Hàng</a></li>
                                {{-- Thay 'cong-nghe', 'ngan-hang' bằng ID hoặc slug thực tế của category --}}
                                <li><a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Đầu tư</a></li>
                                <li><a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Đời sống</a></li>
                            </ul>
                        </div>

                        {{-- Cột 4: Đăng ký nhận tin --}}
                        <div>
                            <h5 class="font-semibold text-gray-800 dark:text-gray-100 uppercase tracking-wider">Đăng ký nhận tin</h5>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Nhận thông báo bài viết mới nhất qua email của bạn.</p>
                            <div class="mt-4 flex">
                                <label for="footer-email-input" class="sr-only">Email</label>
                                <input type="email" id="footer-email-input" placeholder="Email của bạn"
                                       class="w-full text-sm rounded-l-md border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:bg-gray-700 dark:text-gray-200" required>
                                <button type="button" id="footer-subscribe-button"
                                        class="flex-shrink-0 px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-r-md hover:bg-indigo-700 dark:hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors">
                                    Đăng ký
                                </button>
                            </div>
                        </div>

                    </div>

                    {{-- Thanh Copyright --}}
                    <div class="mt-10 border-t border-gray-200 dark:border-gray-700 pt-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Đã đăng ký bản quyền.
                        </p>
                    </div>
                </div>
            </footer>
            {{-- =============== KẾT THÚC FOOTER =============== --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const subscribeButton = document.getElementById('footer-subscribe-button');
        const emailInput = document.getElementById('footer-email-input');

        if (subscribeButton && emailInput) {
            subscribeButton.addEventListener('click', function () {
                const email = emailInput.value;
                if (email) {
                    // Chuyển hướng đến trang liên hệ với email là một query parameter
                    window.location.href = `{{ route('guest.contact') }}?email=${encodeURIComponent(email)}`;
                }
            });
        }
    });
</script>