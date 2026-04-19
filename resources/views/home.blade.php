<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - AuVerse</title>

    @vite(['resources/css/app.css'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Fix Warning VS Code: Tambahin standar line-clamp */
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="bg-[#f7f6fb] text-[#222222]">

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

    <section class="relative overflow-hidden bg-[linear-gradient(180deg,#f1edff_0%,#f7f6fb_100%)]">
        <div class="mx-auto max-w-[1200px] px-6 pb-12 pt-8">
            <div class="grid items-start gap-6 md:grid-cols-[1.15fr_260px]">
                <div>
                    <span class="inline-block rounded-full bg-[#e9e1ff] px-3 py-1 text-[11px] font-semibold text-[#8b6cff]">Comic Collection</span>
                    <h1 class="mt-3 text-[26px] font-extrabold leading-tight text-[#222222] md:text-[42px]">
                        Welcome back, {{ explode(' ', Auth::user()->name ?? 'User')[0] }}!
                    </h1>
                    <p class="mt-2 max-w-[640px] text-[15px] leading-7 text-[#6f6f79]">Jelajahi daftar AU dengan tampilan clean dan modern.</p>

                    <form method="GET" action="{{ route('home') }}" class="mt-8 flex flex-col gap-3 md:flex-row">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari judul komik..."
                            class="h-12 w-full rounded-xl border border-[#ddd7ef] bg-white px-4 text-sm text-[#222222] outline-none focus:border-[#7B61FF] focus:ring-2 focus:ring-[#7B61FF]/20 md:max-w-[560px]"
                        >

                        <select
                            name="genre"
                            class="h-12 rounded-xl border border-[#ddd7ef] bg-white px-4 text-sm text-[#222222] outline-none focus:border-[#7B61FF] focus:ring-2 focus:ring-[#7B61FF]/20 md:w-[200px]"
                        >
                            <option value="">Semua Genre</option>
                            @isset($genres)
                                @foreach($genres as $g)
                                    <option value="{{ $g->nama_genre }}" {{ request('genre') == $g->nama_genre ? 'selected' : '' }}>
                                        {{ $g->nama_genre }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>

                        <button type="submit" class="h-12 rounded-xl bg-[#7B61FF] px-6 text-sm font-semibold text-white transition hover:bg-[#6237ea]">
                            Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-16 pt-6">
        <div class="mx-auto max-w-[1200px] px-6">
            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse($ceritas as $cerita)
                    <div class="rounded-[24px] border border-[#ddd3fb] bg-white p-5 shadow-sm flex flex-col h-full relative group">
                        
                        <div class="absolute top-4 right-4 z-10">
                            <form action="{{ route('user.favorite.toggle', $cerita->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-full transition-all duration-300 shadow-sm 
                                    {{ Auth::user()->favorites->contains($cerita->id) ? 'bg-red-50 text-red-500 hover:bg-red-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                                    @if(Auth::user()->favorites->contains($cerita->id))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        </div>

                        <div class="flex flex-wrap gap-2 pr-12"> 
                            @foreach($cerita->genres as $genreItem)
                                <span class="inline-block rounded-full bg-[#efe7ff] px-3 py-1 text-[11px] font-semibold text-[#8b6cff]">
                                    {{ $genreItem->nama_genre }}
                                </span>
                            @endforeach
                        </div>
                        <h3 class="mt-3 text-[18px] font-extrabold leading-tight text-[#222222] line-clamp-1 pr-8">{{ $cerita->judul }}</h3>
                        <p class="mt-1 text-[13px] leading-7 text-[#6d6d76] line-clamp-2 flex-grow">
                            {{ $cerita->sinopsis ?? $cerita->isi_cerita }}
                        </p>
                        <a href="{{ route('cerita.baca', $cerita->id) }}" class="mt-3 block rounded-xl bg-[#6f42f5] py-3 text-center text-[12px] font-semibold text-white shadow-md hover:bg-[#5b32d4] transition">Baca Sekarang</a>                    </div>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <p class="text-[#6d6d76]">Data tidak ditemukan.</p>
                        <a href="{{ route('home') }}" class="text-[#7B61FF] font-semibold">Reset Pencarian</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</body>
</html>