<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Slot cho Header Scripts (CSS DataTables...) --}}
        {{ $header_scripts ?? '' }}

        {{-- Nhúng SweetAlert trong head --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased" @if(session('success')) data-success-message="{{ session('success') }}" @endif>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Lấy thẻ body
                const body = document.body;
                // Lấy thông báo từ data attribute
                const successMessage = body.getAttribute('data-success-message');

                // Nếu có thông báo
                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: successMessage, // Lấy title từ data attribute
                        showConfirmButton: false,
                        timer: 2000,
                        background: '#1f2937', // Màu nền tối
                        color: '#f3f4f6',      // Màu chữ sáng
                        timerProgressBar: true
                    });
                    body.removeAttribute('data-success-message');
                }
            });
        </script>

        {{-- Slot cho Footer Scripts (JS DataTables...) --}}
        {{ $footer_scripts ?? '' }}

    </body>
</html>