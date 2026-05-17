<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP Publisher</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f5f7] flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900">Verifikasi OTP Publisher</h1>
        <p class="mt-2 text-sm text-gray-600">Masukkan kode OTP 6 digit yang dikirim ke email Anda.</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg bg-green-100 px-3 py-2 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded-lg bg-red-100 px-3 py-2 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('publisher.otp.verify') }}" method="POST" class="mt-5 space-y-4">
            @csrf
            <div>
                <label for="otp" class="mb-1 block text-sm font-semibold text-gray-800">Kode OTP</label>
                <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" required
                       class="h-11 w-full rounded-xl border border-gray-300 px-4 text-sm outline-none focus:border-[#7B4DFF] focus:ring-2 focus:ring-[#7B4DFF]"
                       placeholder="Contoh: 123456" value="{{ old('otp') }}">
            </div>

            <button type="submit" class="h-11 w-full rounded-xl bg-[#6C63FF] text-sm font-semibold text-white hover:opacity-95">
                Verifikasi dan Login
            </button>
        </form>

        <form action="{{ route('publisher.otp.resend') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="w-full text-sm font-medium text-[#6C63FF] hover:underline">Kirim ulang OTP</button>
        </form>

        <a href="{{ route('login') }}" class="mt-4 block text-center text-sm text-gray-500 hover:underline">Kembali ke login</a>
    </div>
</body>
</html>
