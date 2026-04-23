<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - AuVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#7139ff15 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-pattern font-sans antialiased text-gray-900">

    <div class="relative flex flex-col items-center justify-center min-h-screen px-4 overflow-hidden">
        <div class="absolute top-[-10%] left-[-5%] w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="glass-card w-full max-w-md rounded-[2.5rem] shadow-[0_20px_50px_rgba(113,57,255,0.1)] p-10 border border-white/60 relative z-10">
            
            <div class="flex justify-center mb-8">
                <div class="relative">
                    <div class="absolute inset-0 bg-purple-400 blur-xl opacity-20 rounded-full"></div>
                    <div class="relative w-20 h-20 bg-gradient-to-tr from-[#7139ff] to-[#a885ff] rounded-[1.5rem] flex items-center justify-center text-white font-black text-2xl shadow-lg transform -rotate-3">
                        AV
                    </div>
                </div>
            </div>

            <h2 class="text-3xl font-black text-center text-gray-900 mb-3 tracking-tight">
                Cek Email Lo Dulu! <span class="inline-block animate-bounce">🚀</span>
            </h2>

            <div class="mb-8 text-[15px] text-gray-600 leading-relaxed text-center px-2">
                Makasih udah gabung di <span class="font-bold text-[#7139ff]">AuVerse</span>! 
                Langkah terakhir sebelum lo bisa santai baca AU: verifikasi email lo dulu ya. 
                Cukup klik <span class="italic font-medium text-gray-800">link</span> yang baru aja kita kirim.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-8 font-medium text-sm text-purple-700 bg-purple-50/50 backdrop-blur-sm p-4 rounded-2xl text-center border border-purple-100 animate-pulse">
                    ✨ Sip! Link baru udah meluncur ke inbox lo.
                </div>
            @endif

            <div class="space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="group relative w-full flex items-center justify-center px-8 py-4 bg-[#7139ff] hover:bg-[#5b2bd1] rounded-2xl font-bold text-white transition-all duration-300 shadow-[0_10px_25px_-5px_rgba(113,57,255,0.4)] hover:scale-[1.02] active:scale-95">
                        <span>Kirim Ulang Link</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-sm text-gray-400 hover:text-red-500 font-medium transition-colors duration-200">
                        Bukan akun lo? <span class="underline underline-offset-4">Log Out</span>
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-8 text-gray-400 text-xs tracking-widest uppercase font-bold">
            &copy; Alternative Universe
        </p>
    </div>

</body>
</html>