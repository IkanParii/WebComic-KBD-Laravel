<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - AuVerse</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-gray-900">

    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        
        <div class="w-full max-w-md bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 sm:p-10 border border-gray-100">
            
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center text-[#7139ff] font-bold text-2xl shadow-inner">
                    AV
                </div>
            </div>

            <h2 class="text-2xl font-extrabold text-center text-gray-800 mb-4 tracking-tight">Cek Email Lo Dulu! 🚀</h2>

            <div class="mb-6 text-sm text-gray-600 leading-relaxed text-center">
                Makasih udah gabung di <span class="font-bold text-gray-800">AuVerse</span>! Sebelum mulai baca dan nyimpen komik favorit lo, tolong verifikasi alamat email dengan ngeklik <span class="italic">link</span> yang baru aja kita kirim. Kalau emailnya nggak masuk, kita bisa kirim ulang kok.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 font-medium text-sm text-green-700 bg-green-50 p-4 rounded-xl text-center border border-green-200">
                    Sip! Link verifikasi yang baru udah dikirim ke email lo. Cek folder Inbox atau Spam ya.
                </div>
            @endif

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-5">
                
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-6 py-3 bg-[#7139ff] hover:bg-[#5b2bd1] border border-transparent rounded-xl font-semibold text-white focus:outline-none focus:ring-4 focus:ring-purple-200 transition-all duration-300 shadow-md shadow-purple-200/50">
                        Kirim Ulang Link
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-[#7139ff] font-semibold underline underline-offset-4 focus:outline-none transition-colors duration-200">
                        Log Out
                    </button>
                </form>
            </div>
            
        </div>
    </div>

</body>
</html>