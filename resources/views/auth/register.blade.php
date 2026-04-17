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
<body class="min-h-screen bg-[#f3f3f5] flex items-center justify-center p-4 md:p-8">
    <div class="w-full max-w-6xl overflow-hidden rounded-[34px] border border-[#d9cffd] bg-white shadow-sm">
        <div class="grid min-h-[720px] md:grid-cols-2">

            <!-- Left Section -->
            <div class="relative hidden md:block overflow-hidden bg-gradient-to-br from-[#8f4df7] via-[#7c5cf5] to-[#5f67f3] p-10 lg:p-14">
                <div class="absolute top-0 right-0 h-44 w-44 translate-x-8 -translate-y-2 rounded-full bg-white/10"></div>
                <div class="absolute bottom-0 left-0 h-28 w-28 -translate-x-5 translate-y-5 rounded-full bg-white/10"></div>

                <div class="relative z-10 max-w-md text-white">
                    <div class="mb-8 flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-lg">
                        <span class="text-2xl font-bold text-[#6A5AF9]">AV</span>
                    </div>

                    <h1 class="mb-5 text-5xl font-bold tracking-tight">AuVerse</h1>

                    <p class="text-[18px] leading-10 text-white/95">
                        Masuki dunia favoritmu, simpan daftar cerita pilihan, tandai
                        karakter favoritmu, dan jelajahi berbagai vibe AU yang seru.
                    </p>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center justify-center bg-[#fcfcfd] px-8 py-10 sm:px-12 lg:px-14">
                <div class="w-full max-w-xl">
                    <h2 class="text-5xl font-extrabold tracking-tight text-[#111111]">Register</h2>
                    <p class="mt-2 mb-8 text-[18px] text-[#6b7280]">Welcome back, senpai</p>

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl bg-red-100 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Role -->
                        <div class="text-center">
                            <p class="mb-3 text-[15px] font-medium text-[#111111]">Daftar Sebagai</p>

                            <div class="inline-flex rounded-full bg-gradient-to-r from-[#9b50f7] to-[#6c63ff] p-1 text-white shadow-md">
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="role"
                                        value="user"
                                        class="peer sr-only"
                                        {{ old('role', 'user') === 'user' ? 'checked' : '' }}
                                        onchange="togglePublisherField()"
                                    >
                                    <span class="block rounded-full px-6 py-2 text-[15px] font-semibold transition peer-checked:bg-[#7f2de2]">
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
                                    <span class="block rounded-full px-6 py-2 text-[15px] font-semibold transition peer-checked:bg-[#7f2de2]">
                                        Publisher
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="name" class="mb-2 block text-[17px] font-semibold text-[#111111]">
                                Username
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                class="h-14 w-full rounded-2xl border border-[#9ca3af] bg-white px-5 text-[16px] text-[#111111] outline-none transition focus:border-[#7B61FF] focus:ring-2 focus:ring-[#7B61FF]/20"
                            >
                        </div>

                        <!-- Publisher Name -->
                        <div id="publisher-field-wrapper" class="hidden">
                            <label for="publisher_name" class="mb-2 block text-[17px] font-semibold text-[#111111]">
                                Nama Publisher
                            </label>
                            <input
                                id="publisher_name"
                                name="publisher_name"
                                type="text"
                                value="{{ old('publisher_name') }}"
                                placeholder="Masukkan nama publisher"
                                class="h-14 w-full rounded-2xl border border-[#9ca3af] bg-white px-5 text-[16px] text-[#111111] outline-none transition focus:border-[#7B61FF] focus:ring-2 focus:ring-[#7B61FF]/20"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="mb-2 block text-[17px] font-semibold text-[#111111]">
                                Email
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="contoh@gmail.com"
                                required
                                class="h-14 w-full rounded-2xl border border-[#9ca3af] bg-white px-5 text-[16px] text-[#6b7280] outline-none transition focus:border-[#7B61FF] focus:ring-2 focus:ring-[#7B61FF]/20"
                            >
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="mb-2 block text-[17px] font-semibold text-[#111111]">
                                Password
                            </label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Masukkan Password"
                                required
                                class="h-14 w-full rounded-2xl border border-[#9ca3af] bg-white px-5 text-[16px] text-[#6b7280] outline-none transition focus:border-[#7B61FF] focus:ring-2 focus:ring-[#7B61FF]/20"
                            >
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-[17px] font-semibold text-[#111111]">
                                Konfirmasi Password
                            </label>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                placeholder="Ulangi Password"
                                required
                                class="h-14 w-full rounded-2xl border border-[#9ca3af] bg-white px-5 text-[16px] text-[#6b7280] outline-none transition focus:border-[#7B61FF] focus:ring-2 focus:ring-[#7B61FF]/20"
                            >
                        </div>

                        <!-- Submit -->
                        <div class="pt-1">
                            <button
                                type="submit"
                                class="h-14 w-full rounded-2xl bg-gradient-to-r from-[#9b50f7] to-[#5f67f3] text-[17px] font-semibold text-white shadow-[0_10px_22px_rgba(123,97,255,0.28)] transition hover:opacity-95"
                            >
                                Daftar Sekarang
                            </button>
                        </div>

                        <!-- Login -->
                        <p class="pt-1 text-center text-[16px] text-[#6b7280]">
                            Sudah punya akun ?
                            <a href="{{ route('login') }}" class="font-semibold text-[#7B61FF] hover:underline">
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

        document.addEventListener('DOMContentLoaded', function () {
            togglePublisherField();
        });
    </script>
</body>
</html>