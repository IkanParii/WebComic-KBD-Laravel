<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - AuVerse</title>
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
                    AV
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-4">AuVerse</h1>

                <p class="text-white/95 text-base md:text-[17px] leading-8">
                    Kembali ke duniamu, atur ulang passwordmu, 
                    dan lanjutkan petualangan bersama cerita serta karakter favoritmu.
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="bg-[#fafafa] flex items-center justify-center px-6 sm:px-10 md:px-14 py-10">
            <div class="w-full max-w-[460px]">
                <h2 class="text-[34px] md:text-[42px] font-extrabold text-[#1f1f28] leading-tight">
                    Forgot Password
                </h2>
                <p class="text-[#7a7a89] text-[15px] mt-1 mb-8">
                    Please recreate your Password
                </p>

                @if (session('status'))
                    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-[16px] md:text-[17px] font-semibold text-[#1f1f28] mb-2">
                            Recovery Email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="contoh@gmail.com"
                            required
                            autofocus
                            class="w-full h-[56px] md:h-[60px] rounded-[16px] md:rounded-[18px] border border-[#232323] bg-white px-5 text-[16px] md:text-[17px] text-[#4a4a5a] placeholder:text-[#8a8aa0] outline-none focus:border-[#7c4dff] focus:ring-4 focus:ring-[#7c4dff]/10"
                        >
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

</body>
</html>