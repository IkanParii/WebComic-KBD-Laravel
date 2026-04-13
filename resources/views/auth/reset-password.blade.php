<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - AuStory</title>
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

        <!-- LEFT -->
        <div class="relative bg-gradient-to-br from-[#7c4dff] via-[#7b57f6] to-[#8b5cf6] text-white px-8 md:px-10 py-10 md:py-12 overflow-hidden">
            <div class="absolute top-[-40px] right-[-30px] w-[220px] h-[220px] rounded-full bg-white/10"></div>
            <div class="absolute bottom-[-40px] left-[-40px] w-[150px] h-[150px] rounded-full bg-white/10"></div>

            <div class="relative z-10 max-w-[420px]">
                <div class="w-[72px] h-[72px] md:w-[82px] md:h-[82px] rounded-[18px] bg-white text-[#7c4dff] flex items-center justify-center text-[22px] md:text-[26px] font-bold shadow-lg mb-6">
                    AS
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-4">AuStory</h1>

                <p class="text-white/95 text-base md:text-[17px] leading-8">
                    Masuk ke dunia anime favoritmu. Simpan list tontonan, karakter favorit,
                    dan jelajahi vibe comic anime yang seru.
                </p>
            </div>
        </div>

        <!-- RIGHT -->
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
    </script>
</body>
</html>