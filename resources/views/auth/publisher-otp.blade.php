<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - AuVerse</title>

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
            <div class="relative hidden md:block bg-gradient-to-br from-[#7B4DFF] to-[#6C63FF] p-10 lg:p-14 overflow-hidden">
                <div class="absolute top-0 right-0 h-44 w-44 rounded-full bg-white/10 translate-x-10 -translate-y-6"></div>
                <div class="absolute bottom-0 left-0 h-28 w-28 rounded-full bg-white/10 -translate-x-6 translate-y-6"></div>

                <div class="relative z-10 max-w-sm text-white">
                    <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-lg">
                        <span class="text-2xl font-bold text-[#6C63FF]">AV</span>
                    </div>

                    <h1 class="mb-4 text-4xl font-bold leading-tight">Verifikasi Publisher</h1>

                    <p class="text-base leading-8 text-white/95">
                        Satu langkah lagi. Masukkan kode OTP untuk melanjutkan akses ke dashboard publisher AuVerse.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-center bg-[#fcfcfd] px-6 py-10 sm:px-10 lg:px-12">
                <div class="w-full max-w-md">
                    <h2 class="text-4xl font-bold text-[#1f1f1f]">Masukkan OTP</h2>
                    <p class="mt-2 mb-8 text-sm text-gray-500">Kode OTP 6 digit sudah dikirim ke email Anda</p>

                    @if (session('status'))
                        <div class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-700">
                            {{ session('status') }}
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

                    <form action="{{ route('publisher.otp.verify') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="otp" class="mb-2 block text-sm font-semibold text-[#262626]">Kode OTP</label>
                            <input
                                id="otp"
                                name="otp"
                                type="text"
                                inputmode="numeric"
                                maxlength="6"
                                value="{{ old('otp') }}"
                                placeholder="Contoh: 123456"
                                required
                                autofocus
                                class="h-14 w-full rounded-2xl border border-gray-300 bg-white px-4 text-center text-lg tracking-[0.25em] text-gray-700 outline-none transition focus:border-[#7B4DFF] focus:ring-2 focus:ring-[#7B4DFF]"
                            >
                        </div>

                        <button
                            type="submit"
                            class="h-14 w-full rounded-2xl bg-gradient-to-r from-[#7B4DFF] to-[#6C63FF] text-sm font-semibold text-white shadow-lg shadow-[#7B4DFF]/30 transition hover:opacity-95"
                        >
                            Verifikasi dan Login
                        </button>
                    </form>

                    <form action="{{ route('publisher.otp.resend') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full text-sm font-medium text-[#7B4DFF] hover:underline">
                            Kirim ulang OTP
                        </button>
                    </form>

                    <a href="{{ route('login') }}" class="mt-4 block text-center text-sm text-gray-500 hover:underline">
                        Kembali ke halaman login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
