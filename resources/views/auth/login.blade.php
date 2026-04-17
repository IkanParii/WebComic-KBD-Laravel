<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AuStory</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

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
            <div class="relative hidden md:block bg-gradient-to-br from-[#7B4DFF] to-[#6C63FF] p-10 lg:p-14 overflow-hidden">
                <div class="absolute top-0 right-0 h-44 w-44 rounded-full bg-white/10 translate-x-10 -translate-y-6"></div>
                <div class="absolute bottom-0 left-0 h-28 w-28 rounded-full bg-white/10 -translate-x-6 translate-y-6"></div>

                <div class="relative z-10 max-w-sm text-white">
                    <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-lg">
                        <span class="text-2xl font-bold text-[#6C63FF]">AS</span>
                    </div>

                    <h1 class="mb-4 text-4xl font-bold leading-tight">AuStory</h1>

                    <p class="text-base leading-8 text-white/95">
                        Jelajahi dunia favoritmu, simpan list bacaan, temukan karakter favorit, 
                        dan rasakan berbagai vibe AU yang seru.
                    </p>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center justify-center bg-[#fcfcfd] px-6 py-10 sm:px-10 lg:px-12">
                <div class="w-full max-w-md">
                    <h2 class="text-4xl font-bold text-[#1f1f1f]">Login</h2>
                    <p class="mt-2 mb-8 text-sm text-gray-500">Welcome back, senpai</p>

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

                    <form action="{{ route('login') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-[#262626]">
                                Email
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="contoh@gmail.com"
                                required
                                autofocus
                                class="h-14 w-full rounded-2xl border border-gray-300 bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-[#7B4DFF] focus:ring-2 focus:ring-[#7B4DFF]"
                            >
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-[#262626]">
                                Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Masukkan Password"
                                    required
                                    class="h-14 w-full rounded-2xl border border-gray-300 bg-white px-4 pr-12 text-sm text-gray-700 outline-none transition focus:border-[#7B4DFF] focus:ring-2 focus:ring-[#7B4DFF]"
                                >
                                <button
                                    type="button"
                                    onclick="togglePassword()"
                                    class="absolute inset-y-0 right-4 flex items-center text-[#7B4DFF]"
                                >
                                    <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                    <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none"
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

                        <!-- Remember / Forgot -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 text-gray-500">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="h-4 w-4 rounded border-gray-300 text-[#7B4DFF] focus:ring-[#7B4DFF]"
                                >
                                Remember me
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[#7B4DFF] hover:underline">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>

                        <!-- Button -->
                        <button
                            type="submit"
                            class="h-14 w-full rounded-2xl bg-gradient-to-r from-[#7B4DFF] to-[#6C63FF] text-sm font-semibold text-white shadow-lg shadow-[#7B4DFF]/30 transition hover:opacity-95"
                        >
                            Masuk Sekarang
                        </button>

                        <!-- Register -->
                        <p class="text-center text-sm text-gray-500">
                            Belum punya akun ?
                            <a href="{{ route('register') }}" class="font-medium text-[#7B4DFF] hover:underline">
                                Daftar di sini
                            </a>
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

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
    </script>
</body>
</html>