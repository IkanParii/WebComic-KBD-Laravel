<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - AuVerse</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f5f9] flex min-h-screen text-[#222222]">

    <aside class="w-[280px] bg-[#6f42f5] flex flex-col pt-8 fixed h-full shadow-xl z-10 text-white">
        <div class="px-8 flex items-center gap-4 mb-12">
            <div class="w-12 h-12 bg-white rounded-full flex justify-center items-center text-[#6f42f5] font-extrabold text-[18px] shadow-sm">
                AV
            </div>
            <div>
                <h1 class="font-extrabold text-[22px] leading-none">AuVerse</h1>
                <p class="text-[12px] font-medium text-white/80 mt-1">Reader Panel</p>
            </div>
        </div>

        <nav class="flex flex-col gap-2 px-6">
            <a href="#" class="bg-white/20 px-6 py-4 rounded-[14px] font-bold text-[14px] text-white transition">
                Dashboard User
            </a>
            <a href="{{ route('home') }}" class="px-6 py-4 rounded-[14px] font-semibold text-[14px] text-white/70 transition hover:bg-white/10 hover:text-white">
                Back to Site
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-[280px] p-10 lg:p-14">
        <div class="mb-10">
            <h2 class="text-[34px] font-extrabold text-[#222222] tracking-tight">Overview</h2>
            <p class="text-[#8e8e99] mt-1 text-[15px] font-medium">Kelola daftar AU favorit lo dengan cepat.</p>
        </div>

        <div class="bg-white rounded-[24px] p-8 shadow-[0_2px_10px_rgba(0,0,0,0.02)] w-full">
            <h3 class="text-[22px] font-extrabold text-[#222222] mb-6">Daftar AU Favorit</h3>
            
            <div class="flex flex-col">
                <div class="flex justify-between items-center pb-4 border-b border-[#f0eef5]">
                    <span class="text-[14px] font-bold text-[#8e8e99]">Judul AU</span>
                    <span class="text-[14px] font-bold text-[#8e8e99] w-[100px] text-right">Aksi</span>
                </div>

                <div class="flex flex-col mt-2">
                    @forelse($favoritCeritas as $cerita)
                        <div class="flex justify-between items-center py-5 border-b border-[#f4f3f8] last:border-0 group">
                            <div class="pr-6">
                                <h4 class="font-extrabold text-[#222222] text-[16px] group-hover:text-[#6f42f5] transition">{{ $cerita->judul }}</h4>
                                <p class="text-[13px] font-medium text-[#8e8e99] mt-1">
                                    Disimpan pada: {{ $cerita->pivot->created_at ? $cerita->pivot->created_at->format('d M Y') : 'Baru saja' }}
                                </p>
                            </div>
                            
                            <div class="w-[100px] text-right">
                                <form action="{{ route('user.favorite.toggle', $cerita->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="font-bold text-[#eb4d4b] text-[14px] hover:text-[#c0392b] transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center flex flex-col items-center justify-center">
                            <div class="text-5xl mb-4 opacity-50">📭</div>
                            <p class="font-extrabold text-[#222222] text-[18px]">Belum ada AU favorit</p>
                            <p class="text-[14px] font-medium text-[#8e8e99] mt-1 max-w-[300px]">Lo belum nambahin komik apa-apa ke daftar ini. Yuk cari yang seru!</p>
                            <a href="{{ route('home') }}" class="inline-block mt-6 bg-[#f0ebff] text-[#6f42f5] px-6 py-3 rounded-xl font-bold text-[14px] hover:bg-[#e4d9ff] transition">
                                Cari AU Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

</body>
</html>