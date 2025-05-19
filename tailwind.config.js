import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dark: {
                    background: '#18181b', // Nền tối mặc định
                    text: '#f4f4f5',       // Màu chữ sáng
                    card: '#27272a',       // Màu nền card
                    border: '#3f3f46',     // Màu đường viền
                },
            },
        },
    },

    darkMode: 'class', // Kích hoạt Dark Mode theo class (mặc định)
    plugins: [forms],
};
