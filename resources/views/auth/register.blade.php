<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AuVerse</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f5f5f7] flex items-center justify-center p-4 md:p-8">
    <div class="w-full max-w-5xl overflow-hidden rounded-[30px] border border-[#d9cfff] bg-white shadow-sm">
        <div class="grid min-h-[620px] md:grid-cols-2">

            <!-- Left Section -->
            <div class="relative hidden md:block bg-gradient-to-br from-[#7B4DFF] to-[#6C63FF] p-8 lg:p-10 overflow-hidden">
                <div class="absolute top-0 right-0 h-40 w-40 rounded-full bg-white/10 translate-x-8 -translate-y-4"></div>
                <div class="absolute bottom-0 left-0 h-24 w-24 rounded-full bg-white/10 -translate-x-4 translate-y-4"></div>

                <div class="relative z-10 max-w-sm text-white">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-lg">
                        <span class="text-2xl font-bold text-[#6C63FF]">AV</span>
                    </div>

                    <h1 class="mb-4 text-4xl font-bold leading-tight">AuVerse</h1>

                    <p class="text-base leading-8 text-white/95">
                        Masuk ke dunia AU favoritmu. Simpan cerita kesukaanmu, AU favorit,
                        dan jelajahi pengalaman membaca yang lebih seru dan imajinatif.
                    </p>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center justify-center bg-[#fcfcfd] px-6 py-6 sm:px-8">
                <div class="w-full max-w-md">
                    <h2 class="text-3xl font-bold text-[#1f1f1f]">Register</h2>
                    <p class="mt-1 mb-5 text-sm text-gray-500">Welcome back, Reader</p>

                    @if (session('success'))
                        <div class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-3">
                        @csrf

                        <!-- Role -->
                        <div class="text-center">
                            <p class="mb-2 text-sm font-medium text-[#111111]">Daftar Sebagai</p>

                            <div class="inline-flex rounded-full bg-gradient-to-r from-[#7B4DFF] to-[#6C63FF] p-1 text-white shadow-md">
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="role"
                                        value="user"
                                        class="peer sr-only"
                                        {{ old('role', 'user') === 'user' ? 'checked' : '' }}
                                        onchange="togglePublisherField()"
                                    >
                                    <span class="block rounded-full px-5 py-1.5 text-sm font-semibold transition peer-checked:bg-[#5b35e8]">
                                        User
                                    </span>
                                </label>

                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="role"
                                        value="publisher"
                                        class="peer sr-only"
                                        {{ old('role') === 'publisher' ? 'checked' : '' }}
                                        onchange="togglePublisherField()"
                                    >
                                    <span class="block rounded-full px-5 py-1.5 text-sm font-semibold transition peer-checked:bg-[#5b35e8]">
                                        Publisher
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-semibold text-[#262626]">
                                Username
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-[#7B4DFF] focus:ring-2 focus:ring-[#7B4DFF]"
                            >
                        </div>

                        <!-- Publisher Name -->
                        <div id="publisher-field-wrapper" class="hidden">
                            <label for="publisher_name" class="mb-1.5 block text-sm font-semibold text-[#262626]">
                                Nama Publisher
                            </label>
                            <input
                                id="publisher_name"
                                name="publisher_name"
                                type="text"
                                value="{{ old('publisher_name') }}"
                                placeholder="Masukkan nama publisher"
                                class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-[#7B4DFF] focus:ring-2 focus:ring-[#7B4DFF]"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-semibold text-[#262626]">
                                Email
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="contoh@gmail.com"
                                required
                                class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-[#7B4DFF] focus:ring-2 focus:ring-[#7B4DFF]"
                            >
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-semibold text-[#262626]">
                                Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Masukkan Password"
                                    required
                                    class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 pr-12 text-sm text-gray-700 outline-none transition focus:border-[#7B4DFF] focus:ring-2 focus:ring-[#7B4DFF]"
                                >
                                <button
                                    type="button"
                                    onclick="togglePassword('password', 'eye-open-password', 'eye-closed-password')"
                                    class="absolute inset-y-0 right-4 flex items-center text-[#7B4DFF]"
                                >
                                    <svg id="eye-open-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                    <svg id="eye-closed-password" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3l18 18" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.58 10.58A2 2 0 0012 14a2 2 0 001.42-.58" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.88 5.09A9.77 9.77 0 0112 4.5c5 0 8.27 4.11 9 5.5a13.16 13.16 0 01-2.3 2.96M6.61 6.61C4.62 8.01 3.35 9.86 3 10.5c.73 1.39 4 5.5 9 5.5a9.8 9.8 0 003.39-.61" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-[#262626]">
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    placeholder="Ulangi Password"
                                    required
                                    class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 pr-12 text-sm text-gray-700 outline-none transition focus:border-[#7B4DFF] focus:ring-2 focus:ring-[#7B4DFF]"
                                >
                                <button
                                    type="button"
                                    onclick="togglePassword('password_confirmation', 'eye-open-confirm', 'eye-closed-confirm')"
                                    class="absolute inset-y-0 right-4 flex items-center text-[#7B4DFF]"
                                >
                                    <svg id="eye-open-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                    <svg id="eye-closed-confirm" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3l18 18" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.58 10.58A2 2 0 0012 14a2 2 0 001.42-.58" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.88 5.09A9.77 9.77 0 0112 4.5c5 0 8.27 4.11 9 5.5a13.16 13.16 0 01-2.3 2.96M6.61 6.61C4.62 8.01 3.35 9.86 3 10.5c.73 1.39 4 5.5 9 5.5a9.8 9.8 0 003.39-.61" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Button -->
                        <button
                            type="submit"
                            class="h-11 w-full rounded-xl bg-gradient-to-r from-[#7B4DFF] to-[#6C63FF] text-sm font-semibold text-white shadow-lg shadow-[#7B4DFF]/30 transition hover:opacity-95"
                        >
                            Daftar Sekarang
                        </button>

                        <!-- Login -->
                        <p class="text-center text-sm text-gray-500">
                            Sudah punya akun ?
                            <a href="{{ route('login') }}" class="font-medium text-[#7B4DFF] hover:underline">
                                Login di sini
                            </a>
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePublisherField() {
            const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
            const wrapper = document.getElementById('publisher-field-wrapper');
            const input = document.getElementById('publisher_name');

            if (selectedRole === 'publisher') {
                wrapper.classList.remove('hidden');
                input.setAttribute('required', 'required');
            } else {
                wrapper.classList.add('hidden');
                input.removeAttribute('required');
                input.value = '';
            }
        }

        function togglePassword(inputId, eyeOpenId, eyeClosedId) {
            const passwordInput = document.getElementById(inputId);
            const eyeOpen = document.getElementById(eyeOpenId);
            const eyeClosed = document.getElementById(eyeClosedId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            togglePublisherField();
        });
    </script>
</body>
</html>