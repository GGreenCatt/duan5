<x-guest-layout>
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Đăng ký thành công',
                    showConfirmButton: false,
                    timer: 1000
                });
            });
        </script>
    @endif

    <form method="POST" action="{{ route('register') }}" onsubmit="return validateForm()">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Tên')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required autofocus="name"
                        oninput="checkName()" />
                          <small id="name-error" class="text-red-500 hidden mt-1"></small>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autocomplete="username" oninput="checkEmail()" />
            <small id="email-error" class="text-red-500 hidden mt-1"></small>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mật khẩu')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password" name="password" required autocomplete="new-password"
                          oninput="checkPasswordStrength()" />
            <small id="password-error" class="text-red-500 hidden mt-1"></small>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Nhập lại mật khẩu')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
               href="{{ route('login') }}">
                {{ __('Bạn đã đăng ký rồi sao?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Đăng ký') }}
            </x-primary-button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function checkEmail() {
            const email = document.getElementById("email").value.trim();
            const emailError = document.getElementById("email-error");

            if (email === "") {
                emailError.classList.remove("hidden");
                emailError.innerText = "Email không được để trống.";
            } else if (email.length > 255) {
                emailError.classList.remove("hidden");
                emailError.innerText = "Email không được vượt quá 255 ký tự.";
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                emailError.classList.remove("hidden");
                emailError.innerText = "Email không đúng định dạng.";
            } else {
                emailError.classList.add("hidden");
                emailError.innerText = "";
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById("password").value;
            const passwordError = document.getElementById("password-error");
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (password === "") {
                passwordError.classList.remove("hidden");
                passwordError.innerText = "Mật khẩu không được để trống.";
            } else if (password.length < 8) {
                passwordError.classList.remove("hidden");
                passwordError.innerText = "Mật khẩu phải có ít nhất 8 ký tự.";
            } else if (password.length > 255) {
                passwordError.classList.remove("hidden");
                passwordError.innerText = "Mật khẩu không được vượt quá 255 ký tự.";
            } else if (!regex.test(password)) {
                passwordError.classList.remove("hidden");
                passwordError.innerText =
                    "Mật khẩu phải gồm chữ hoa, chữ thường, số và ký tự đặc biệt.";
            } else {
                passwordError.classList.add("hidden");
                passwordError.innerText = "";
            }
        }

        function checkName() {
            const name = document.getElementById("name").value.trim();
            const nameError = document.getElementById("name-error");

            if (name === "") {
                nameError.classList.remove("hidden");
                nameError.innerText = "Tên không được để trống.";
            } else if (name.length > 255) {
                nameError.classList.remove("hidden");
                nameError.innerText = "Tên không được vượt quá 255 ký tự.";
            } else {
                nameError.classList.add("hidden");
                nameError.innerText = "";
            }
        }

        function validateForm() {
            checkName();
            checkEmail();
            checkPasswordStrength();

            const nameError = document.getElementById("name-error");
            const emailError = document.getElementById("email-error");
            const passwordError = document.getElementById("password-error");

            if (
                !nameError.classList.contains("hidden") ||
                !emailError.classList.contains("hidden") ||
                !passwordError.classList.contains("hidden")
            ) {
                return false;
            }

            return true;
        }
    </script>
</x-guest-layout>
