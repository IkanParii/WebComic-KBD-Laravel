<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - AuVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f3f3f3] flex items-center justify-center p-4 md:p-8">

    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 rounded-[32px] overflow-hidden border-2 border-[#ddd6fe] bg-white shadow-[0_10px_30px_rgba(120,80,255,0.08)] min-h-[640px]">

        <div class="relative bg-gradient-to-br from-[#7c4dff] via-[#7b57f6] to-[#8b5cf6] text-white px-8 md:px-10 py-10 md:py-12 overflow-hidden">
            <div class="absolute top-[-40px] right-[-30px] w-[220px] h-[220px] rounded-full bg-white/10"></div>
            <div class="absolute bottom-[-40px] left-[-40px] w-[150px] h-[150px] rounded-full bg-white/10"></div>

            <div class="relative z-10 max-w-[420px]">
                <div class="w-[72px] h-[72px] md:w-[82px] md:h-[82px] rounded-[18px] bg-white text-[#7c4dff] flex items-center justify-center text-[22px] md:text-[26px] font-bold shadow-lg mb-6">
                    AV
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-4">AuVerse</h1>

                <p class="text-white/95 text-base md:text-[17px] leading-8">
                    Masuk ke dunia AU favoritmu. Simpan list Bacaan, Au favorit,
                    dan jelajahi Berbagai AU yang seru.
                </p>
            </div>
        </div>

        <div class="bg-[#fafafa] flex items-center justify-center px-6 sm:px-10 md:px-14 py-10">
            <div class="w-full max-w-[460px]">
                <h2 class="text-[34px] md:text-[42px] font-extrabold text-[#1f1f28] leading-tight">
                    Reset Password
                </h2>
                <p class="text-[#7a7a89] text-[15px] mt-1 mb-8">
                    Please create your new password
                </p>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="token" value="{{ request()->route('token') }}">

                    <div>
                        <label for="email" class="mb-2 block text-[16px] md:text-[17px] font-semibold text-[#1f1f28]">
                            Recovery Email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', request()->email) }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="contoh@gmail.com"
                            class="w-full h-[56px] md:h-[60px] rounded-[16px] md:rounded-[18px] border border-[#232323] bg-white px-5 text-[16px] md:text-[17px] text-[#4a4a5a] placeholder:text-[#8a8aa0] outline-none focus:border-[#7c4dff] focus:ring-4 focus:ring-[#7c4dff]/10"
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-[16px] md:text-[17px] font-semibold text-[#1f1f28]">
                            New password
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Masukkan Password"
                                class="w-full h-[56px] md:h-[60px] rounded-[16px] md:rounded-[18px] border border-[#232323] bg-white px-5 pr-14 text-[16px] md:text-[17px] text-[#4a4a5a] placeholder:text-[#8a8aa0] outline-none focus:border-[#7c4dff] focus:ring-4 focus:ring-[#7c4dff]/10"
                            >

                            <button
                                type="button"
                                onclick="togglePassword('password', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9d7bff]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-[20px] w-[20px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58A2 2 0 0013.42 13.42"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A9.77 9.77 0 0112 4.8c5.25 0 9 7.2 9 7.2a13.18 13.18 0 01-3.04 3.81M6.61 6.61C4.24 8.24 3 12 3 12s3.75 7.2 9 7.2a9.9 9.9 0 004.18-.9"/>
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
                                <li id="req-length" class="flex items-center gap-1 transition-colors"><span class="text-lg leading-none">&bull;</span> Min 12 karakter</li>
                                <li id="req-upper" class="flex items-center gap-1 transition-colors"><span class="text-lg leading-none">&bull;</span> Huruf kapital</li>
                                <li id="req-lower" class="flex items-center gap-1 transition-colors"><span class="text-lg leading-none">&bull;</span> Huruf kecil</li>
                                <li id="req-symbol" class="flex items-center gap-1 transition-colors"><span class="text-lg leading-none">&bull;</span> Angka / Simbol</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-[16px] md:text-[17px] font-semibold text-[#1f1f28]">
                            Re enter new password
                        </label>
                        <div class="relative">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Masukkan Password"
                                class="w-full h-[56px] md:h-[60px] rounded-[16px] md:rounded-[18px] border border-[#232323] bg-white px-5 pr-14 text-[16px] md:text-[17px] text-[#4a4a5a] placeholder:text-[#8a8aa0] outline-none focus:border-[#7c4dff] focus:ring-4 focus:ring-[#7c4dff]/10"
                            >

                            <button
                                type="button"
                                onclick="togglePassword('password_confirmation', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9d7bff]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-[20px] w-[20px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58A2 2 0 0013.42 13.42"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A9.77 9.77 0 0112 4.8c5.25 0 9 7.2 9 7.2a13.18 13.18 0 01-3.04 3.81M6.61 6.61C4.24 8.24 3 12 3 12s3.75 7.2 9 7.2a9.9 9.9 0 004.18-.9"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full h-[56px] md:h-[60px] rounded-[16px] md:rounded-[18px] bg-gradient-to-r from-[#7c4dff] via-[#7a5af8] to-[#8b5cf6] text-white text-[17px] md:text-[18px] font-semibold shadow-[0_10px_20px_rgba(124,77,255,0.25)] hover:translate-y-[-1px] transition"
                    >
                        Continue
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
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

            // Kriteria persis dengan backend (Global Password Policy)
            const criteria = {
                length: password.length >= 12,
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
            // Pasang event listener biar meternya jalan tiap kali user ngetik
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                passwordInput.addEventListener('input', checkPasswordStrength);
            }
        });
    </script>
</body>
</html>