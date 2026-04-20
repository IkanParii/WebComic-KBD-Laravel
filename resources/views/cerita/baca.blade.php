<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cerita->judul }} - AuVerse</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#f8f9fa] text-[#241b3d]">

    <header class="sticky top-0 z-50 flex w-full items-center justify-between border-b border-[#e6dcff] bg-white/85 px-[6%] py-5 backdrop-blur-md">
        <div class="flex items-center gap-3 text-[1.1rem] font-bold">
            <div class="mb-0 flex h-12 w-12 items-center justify-center rounded-[18px] bg-white text-base font-bold text-[#7b4dff] shadow-[0_8px_20px_rgba(0,0,0,0.12)] uppercase">
                AV
            </div>
            <span>AuVerse</span>
        </div>

        <nav class="flex flex-wrap items-center gap-[18px]">
            <a href="{{ route('home') }}" class="font-semibold text-[#241b3d] no-underline hover:text-[#7b4dff]">Home</a>
            
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="font-semibold text-[#241b3d] no-underline hover:text-[#7b4dff]">Dashboard Admin</a>
            @elseif(Auth::user()->role === 'publisher')
                <a href="{{ route('publisher.index') }}" class="font-semibold text-[#241b3d] no-underline hover:text-[#7b4dff]">Dashboard Publisher</a>
            @elseif(Auth::user()->role === 'user')
                <a href="{{ route('user.dashboard') }}" class="font-semibold text-[#241b3d] no-underline hover:text-[#7b4dff]">My Dashboard</a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl border-2 border-[#e6dcff] bg-white px-5 py-3.5 font-bold text-[#7b4dff] transition duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-500">
                    Logout
                </button>
            </form>
        </nav>
    </header>

    <section class="w-full bg-[#fbf9ff] px-[6%] py-16 border-b border-[#f0eef5]">
        <div class="mx-auto max-w-[1400px]">
            <h1 class="text-[40px] font-extrabold text-[#241b3d] leading-tight">{{ $cerita->judul }}</h1>
            
            <p class="mt-2 text-[15px] font-semibold text-[#7b4dff]">
                Penulis: {{ $cerita->user->nama_publisher ?? 'Unknown Publisher' }}
            </p>
            
            <p class="mt-4 max-w-[700px] text-[15px] leading-relaxed text-[#6d6d76]">
                {{ $cerita->deskripsi_singkat }}
            </p>
        </div>
    </section>

    <main class="mx-auto w-full max-w-[1400px] px-[6%] py-12">
        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-[320px_1fr]">
            
            <div class="sticky top-[120px] rounded-[24px] bg-white p-7 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-[#f0eef5]">
                
                <div class="mb-5 flex flex-wrap gap-2">
                    @foreach($cerita->genres as $genre)
                        <span class="inline-block rounded-full bg-[#f3edff] px-3.5 py-1.5 text-[11px] font-bold text-[#7b4dff]">
                            {{ $genre->nama_genre }}
                        </span>
                    @endforeach
                </div>
                
                <h2 class="text-[20px] font-extrabold text-[#241b3d] leading-snug">{{ $cerita->judul }}</h2>
                
                <p class="mt-1 text-[13px] font-semibold text-[#7b4dff]">
                    Penulis: {{ $cerita->user->nama_publisher ?? 'Unknown Publisher' }}
                </p>

                <p class="mt-3 text-[13px] leading-relaxed text-[#6d6d76]">
                    {{ $cerita->deskripsi_singkat }}
                </p>

                <div class="mt-6 border-t border-[#f0eef5] pt-6">
                    <form action="{{ route('user.favorite.toggle', $cerita->id) }}" method="POST">
                        @csrf
                        @php
                            $isFavorite = Auth::check() && Auth::user()->favorites->contains($cerita->id);
                        @endphp
                        
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-[14px] py-3.5 font-bold text-[14px] transition-all
                            {{ $isFavorite ? 'bg-[#f0eef5] text-[#241b3d] hover:bg-[#e6e4ec]' : 'bg-[#7b4dff] text-white hover:bg-[#6a3ae0] shadow-md hover:shadow-lg' }}">
                            
                            @if($isFavorite)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#eb4d4b]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                Hapus Favorit
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Tambah ke Favorit
                            @endif
                            
                        </button>
                    </form>
                </div>
            </div>

            <div class="rounded-[24px] bg-white p-8 md:p-12 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-[#f0eef5] min-h-[600px]">
                <h3 class="mb-10 text-center text-[22px] font-extrabold text-[#7b4dff]">Panel Baca</h3>
                
                <div class="space-y-6 text-[15px] leading-loose text-[#333333]">
                    {!! nl2br(e($cerita->isi_cerita)) !!}
                </div>
                
                <div class="mt-16 border-t border-[#f0eef5] pt-8 text-center">
                    <p class="mb-4 text-[14px] font-medium text-[#8e8e99]">Lanjut eksplor cerita lainnya?</p>
                    <a href="{{ route('home') }}" class="inline-block rounded-[14px] bg-[#f0eef5] px-6 py-3 font-bold text-[#241b3d] transition hover:bg-[#e6e4ec]">
                        Kembali ke Home
                    </a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>