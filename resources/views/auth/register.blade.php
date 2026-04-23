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

                            <div id="password-strength-container" class="hidden mt-3 transition-all duration-300">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-xs font-medium text-gray-500">Kekuatan Password:</span>
                                    <span id="strength-text" class="text-xs font-bold text-gray-400">Belum diisi</span>
                                </div>
                                <div class="h-1.5 w-full rounded-full bg-gray-200 overflow-hidden flex gap-1">
                                    <div id="bar-1" class="h-full w-1/4 bg-gray-200 transition-colors duration-300 rounded-full"></div>
                                    <div id="bar-2" class="h-full w-1/4 bg-gray-200 transition-colors duration-300 rounded-full"></div>
                                    <div id="bar-3" class="h-full w-1/4 bg-gray-200 transition-colors duration-300 rounded-full"></div>
                                    <div id="bar-4" class="h-full w-1/4 bg-gray-200 transition-colors duration-300 rounded-full"></div>
                                </div>
                                
                                <ul class="mt-2 grid grid-cols-2 gap-1 text-[11px] text-gray-500">
                                    <li id="req-length" class="flex items-center gap-1 transition-colors"><span class="text-lg leading-none">&bull;</span> Min 8 karakter</li>
                                    <li id="req-upper" class="flex items-center gap-1 transition-colors"><span class="text-lg leading-none">&bull;</span> Huruf kapital</li>
                                    <li id="req-lower" class="flex items-center gap-1 transition-colors"><span class="text-lg leading-none">&bull;</span> Huruf kecil</li>
                                    <li id="req-symbol" class="flex items-center gap-1 transition-colors"><span class="text-lg leading-none">&bull;</span> Angka / Simbol</li>
                                </ul>
                            </div>
                            </div>

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

                        <button
                            type="submit"
                            id="btn-submit"
                            class="h-11 w-full mt-2 rounded-xl bg-gradient-to-r from-[#7B4DFF] to-[#6C63FF] text-sm font-semibold text-white shadow-lg shadow-[#7B4DFF]/30 transition hover:opacity-95"
                        >
                            Daftar Sekarang
                        </button>

                        <p class="text-center text-sm text-gray-500 mt-2">
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

        // 👇 TAMBAHAN LOGIC PASSWORD STRENGTH 👇
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const container = document.getElementById('password-strength-container');
            const strengthText = document.getElementById('strength-text');
            const bars = [
                document.getElementById('bar-1'),
                document.getElementById('bar-2'),
                document.getElementById('bar-3'),
                document.getElementById('bar-4')
            ];

            // Tampilkan container kalau mulai ngetik
            if (password.length > 0) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }

            // Kriteria persis dengan backend lo (Global Password Policy)
            const criteria = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                symbol: /[\W_0-9]/.test(password) // Angka atau Spesial Karakter
            };

            // Update UI Checklist
            document.getElementById('req-length').className = `flex items-center gap-1 transition-colors ${criteria.length ? 'text-[#05c46b] font-medium' : 'text-gray-500'}`;
            document.getElementById('req-upper').className = `flex items-center gap-1 transition-colors ${criteria.upper ? 'text-[#05c46b] font-medium' : 'text-gray-500'}`;
            document.getElementById('req-lower').className = `flex items-center gap-1 transition-colors ${criteria.lower ? 'text-[#05c46b] font-medium' : 'text-gray-500'}`;
            document.getElementById('req-symbol').className = `flex items-center gap-1 transition-colors ${criteria.symbol ? 'text-[#05c46b] font-medium' : 'text-gray-500'}`;

            // Hitung Score (0 sampai 4)
            let score = 0;
            if (criteria.length) score++;
            if (criteria.upper) score++;
            if (criteria.lower) score++;
            if (criteria.symbol) score++;

            // Reset warna bar
            bars.forEach(bar => bar.className = 'h-full w-1/4 bg-gray-200 transition-colors duration-300 rounded-full');

            // Logika Warna dan Teks
            if (password.length === 0) {
                strengthText.textContent = 'Belum diisi';
                strengthText.className = 'text-xs font-bold text-gray-400';
            } else if (score === 1) {
                strengthText.textContent = 'Sangat Lemah';
                strengthText.className = 'text-xs font-bold text-[#ff3f34]';
                bars[0].classList.replace('bg-gray-200', 'bg-[#ff3f34]');
            } else if (score === 2) {
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-xs font-bold text-[#ffb8b8]';
                bars[0].classList.replace('bg-gray-200', 'bg-[#ffb8b8]');
                bars[1].classList.replace('bg-gray-200', 'bg-[#ffb8b8]');
            } else if (score === 3) {
                strengthText.textContent = 'Lumayan';
                strengthText.className = 'text-xs font-bold text-[#ffa801]';
                bars[0].classList.replace('bg-gray-200', 'bg-[#ffa801]');
                bars[1].classList.replace('bg-gray-200', 'bg-[#ffa801]');
                bars[2].classList.replace('bg-gray-200', 'bg-[#ffa801]');
            } else if (score === 4) {
                strengthText.textContent = 'Sangat Kuat!';
                strengthText.className = 'text-xs font-bold text-[#05c46b]';
                bars.forEach(bar => bar.classList.replace('bg-gray-200', 'bg-[#05c46b]'));
            }
        }
        // 👆 AKHIR TAMBAHAN LOGIC 👆

        document.addEventListener('DOMContentLoaded', function () {
            togglePublisherField();
            
            // Pasang event listener biar meternya jalan tiap kali user ngetik
            document.getElementById('password').addEventListener('input', checkPasswordStrength);
        });
    </script>
</body>
</html>