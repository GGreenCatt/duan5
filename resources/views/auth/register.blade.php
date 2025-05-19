<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" onsubmit="return validatePassword()">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Tên')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mật khẩu')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password"
                          oninput="checkPasswordStrength()" />

            <small id="password-error" class="text-red-500 mt-2 hidden"></small>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Nhập lại mật khẩu')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Bạn đã đăng ký rồi sao?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Đăng ký') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        // Kiểm tra độ mạnh của mật khẩu
        function checkPasswordStrength() {
            const password = document.getElementById("password").value;
            const passwordError = document.getElementById("password-error");
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (!regex.test(password)) {
                passwordError.classList.remove("hidden");
                passwordError.innerText =
                    "Mật khẩu phải có ít nhất 8 ký tự, bao gồm ít nhất 1 chữ thường, 1 chữ in hoa, 1 số và 1 ký tự đặc biệt.";
            } else {
                passwordError.classList.add("hidden");
                passwordError.innerText = "";
            }
        }

        // Xác thực mật khẩu khi gửi form
        function validatePassword() {
            const password = document.getElementById("password").value;
            const passwordError = document.getElementById("password-error");
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (!regex.test(password)) {
                passwordError.classList.remove("hidden");
                passwordError.innerText =
                    "Mật khẩu phải có ít nhất 8 ký tự, bao gồm ít nhất 1 chữ thường, 1 chữ in hoa, 1 số và 1 ký tự đặc biệt.";
                return false;
            }

            return true;
        }
    </script>
</x-guest-layout>
