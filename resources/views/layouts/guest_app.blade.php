{{-- CẬP NHẬT FILE NÀY: resources/views/layouts/guest_app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fancybox CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

    @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">

            {{-- Navbar Khách (Flowbite) --}}
            <nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
              {{-- ... toàn bộ code navbar Flowbite ... --}}
              <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                  {{-- Logo --}}
                  <a href="{{ route('guest.home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                      <x-application-logo class="block h-8 w-auto fill-current text-gray-800 dark:text-gray-200" />
                      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">{{ config('app.name', 'Laravel') }}</span>
                  </a>
                  {{-- Nút Login/Register hoặc User Menu --}}
                  <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                      @auth
                          {{-- Dropdown người dùng đã đăng nhập --}}
                          <x-dropdown align="right" width="48">
                              <x-slot name="trigger">
                                  <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition ease-in-out duration-150">
                                      <div>{{ Auth::user()->name }}</div>
                                      <div class="ms-1">
                                          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                      </div>
                                  </button>
                              </x-slot>
                              <x-slot name="content">
                                  <x-dropdown-link :href="route('profile.edit')"> {{ __('Tài khoản') }} </x-dropdown-link>
                                  @if(Auth::user()->role === 'Admin')
                                      <x-dropdown-link :href="route('dashboard')"> {{ __('Admin Dashboard') }} </x-dropdown-link>
                                  @endif
                                  <form method="POST" action="{{ route('logout') }}"> @csrf <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"> {{ __('Đăng xuất') }} </x-dropdown-link> </form>
                              </x-slot>
                          </x-dropdown>
                      @else
                          {{-- Nút đăng nhập/đăng ký cho khách --}}
                          <a href="{{ route('login') }}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mx-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Đăng nhập</a>
                          @if (Route::has('register'))
                           <a href="{{ route('register') }}" type="button" class="ml-2 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-700 dark:focus:ring-gray-700 hidden sm:inline-flex">Đăng ký</a>
                          @endif
                      @endauth
                      {{-- Nút Hamburger cho mobile --}}
                      <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false"> <span class="sr-only">Open main menu</span> <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14"> <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/> </svg> </button>
                  </div>
                  {{-- Các link điều hướng chính --}}
                  <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
    <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <li> <a href="{{ route('guest.home') }}" class="block py-2 px-3 rounded md:p-0 {{ request()->routeIs('guest.home') ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 md:dark:text-blue-500' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent dark:border-gray-700' }}" aria-current="{{ request()->routeIs('guest.home') ? 'page' : 'false' }}">Trang chủ</a> </li>
        <li> <a href="{{ route('guest.posts.index') }}" class="block py-2 px-3 rounded md:p-0 {{ (request()->routeIs('guest.posts.index') || request()->routeIs('guest.posts.by_category')) ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 md:dark:text-blue-500' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent dark:border-gray-700' }}" aria-current="{{ (request()->routeIs('guest.posts.index') || request()->routeIs('guest.posts.by_category')) ? 'page' : 'false' }}">Bài viết</a> </li>
        
        <li> <a href="{{ route('guest.categories') }}" class="block py-2 px-3 rounded md:p-0 {{ request()->routeIs('guest.categories') ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 md:dark:text-blue-500' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent dark:border-gray-700' }}" aria-current="{{ request()->routeIs('guest.categories') ? 'page' : 'false' }}">Danh mục</a> </li>

        <li> <a href="{{ route('about') }}" class="block py-2 px-3 rounded md:p-0 {{ request()->routeIs('about') ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 md:dark:text-blue-500' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent dark:border-gray-700' }}" aria-current="{{ request()->routeIs('about') ? 'page' : 'false' }}">Về chúng tôi</a> </li>
    </ul>
</div>
              </div>
            </nav>

            {{-- Kiểm tra xem section 'header' có được định nghĩa không --}}
            @hasSection('header')
                <header class="bg-white dark:bg-gray-800 shadow pt-24"> {{-- Thêm pt-24 --}}
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @yield('header') {{-- Sử dụng @yield thay vì {{ $header }} --}}
                    </div>
                </header>
            @endif

            {{-- Thêm pt-24 nếu không có header --}}
            <main class="flex-grow {{ View::hasSection('header') ? '' : 'pt-24' }}">
                 @yield('content') {{-- Đây là nơi nội dung chính sẽ được chèn vào --}}
            </main>

            {{-- Footer --}}
            @include('layouts.footer') {{-- Include footer nếu bạn đã tách ra --}}
            {{-- Hoặc dán code footer trực tiếp vào đây nếu chưa tách --}}
            
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        // Khởi tạo Fancybox cho tất cả các link có thuộc tính data-fancybox
        Fancybox.bind("[data-fancybox]", {
          // Tùy chọn thêm nếu cần
        });
    </script>
    @stack('scripts')
    </body>
</html>