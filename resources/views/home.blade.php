<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - AuStory</title>

    @vite(['resources/css/app.css'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-[#f7f6fb] text-[#222222]">

    <header class="border border-[#ddd7ef] bg-[#f8f8fb] shadow-sm">
        <div class="mx-auto flex max-w-[1200px] items-center justify-between px-4 py-3">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[11px] font-bold text-[#7B61FF] shadow uppercase">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <span class="text-[15px] font-bold text-[#171717]">AuStory</span>
            </div>

            <nav class="hidden items-center gap-10 md:flex">
                <a href="{{ route('home') }}" class="text-[15px] font-semibold text-[#7B61FF]">Home</a>
                <a href="#" class="text-[15px] font-semibold text-[#222222] hover:text-[#7B61FF]">Komik</a>
                
                @if(Auth::user()->role === 'publisher')
                    <a href="{{ route('publisher.index') }}" class="text-[15px] font-semibold text-[#222222] hover:text-[#7B61FF]">
                        Dashboard Publisher
                    </a>
                @endif
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="rounded-[16px] border border-[#bbb6c8] bg-[#f8f8fb] px-6 py-2 text-[15px] font-semibold text-[#222222] transition hover:bg-red-50 hover:text-red-600 hover:border-red-200">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <section class="relative overflow-hidden bg-[linear-gradient(180deg,#f1edff_0%,#f7f6fb_100%)]">
        <div class="mx-auto max-w-[1200px] px-6 pb-12 pt-8">
            <div class="grid items-start gap-6 md:grid-cols-[1.15fr_260px]">
                <div>
                    <span class="inline-block rounded-full bg-[#e9e1ff] px-3 py-1 text-[11px] font-semibold text-[#8b6cff]">
                        Comic Collection
                    </span>

                    <h1 class="mt-3 text-[26px] font-extrabold leading-tight text-[#222222] md:text-[42px]">
                        Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!
                    </h1>

                    <p class="mt-2 max-w-[640px] text-[15px] leading-7 text-[#6f6f79]">
                        Jelajahi daftar AU dengan tampilan clean, modern, dan nuansa anime
                        yang cocok untuk user muda.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 md:flex-row">
                        <input
                            type="text"
                            placeholder="Cari judul komik..."
                            class="h-12 w-full rounded-xl border border-[#ddd7ef] bg-white px-4 text-sm text-[#222222] outline-none placeholder:text-[#b5b5bf] focus:border-[#7B61FF] focus:ring-2 focus:ring-[#7B61FF]/20 md:max-w-[560px]"
                        >

                        <select
                            class="h-12 rounded-xl border border-[#ddd7ef] bg-white px-4 text-sm text-[#222222] outline-none focus:border-[#7B61FF] focus:ring-2 focus:ring-[#7B61FF]/20 md:w-[250px]"
                        >
                            <option>Semua Genre</option>
                            <option>School</option>
                            <option>Romance</option>
                            <option>Fantasy</option>
                            <option>Action</option>
                        </select>
                    </div>
                </div>

                <div class="relative hidden md:block">
                    <div class="absolute right-4 top-3 h-[170px] w-[170px] rounded-full bg-[#efeaff] blur-2xl"></div>
                    <img
                        src="{{ asset('image/background.jpeg') }}"
                        alt="Character"
                        class="relative ml-auto w-[190px] object-contain opacity-20 grayscale"
                    >
                </div>
            </div>
        </div>
    </section>

    <section class="pb-16 pt-6">
        <div class="mx-auto max-w-[1200px] px-6">
            <div class="text-center">
                <h2 class="text-[24px] font-extrabold text-[#222222] md:text-[36px]">Daftar AU</h2>
                <p class="mt-1 text-[14px] text-[#a0a0aa] md:text-[18px]">
                    Koleksi populer untuk kamu baca hari ini
                </p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-[24px] border border-[#ddd3fb] bg-white p-5 shadow-[0_10px_24px_rgba(123,97,255,0.18)]">
                    <span class="inline-block rounded-full bg-[#efe7ff] px-3 py-1 text-[11px] font-semibold text-[#8b6cff]">
                        School
                    </span>

                    <h3 class="mt-3 text-[18px] font-extrabold leading-tight text-[#222222]">
                        School Senpai
                    </h3>

                    <p class="mt-1 text-[13px] leading-7 text-[#6d6d76]">
                        Cerita kehidupan sekolah yang penuh drama, persahabatan, dan cinta.
                    </p>

                    <div class="mt-5 flex items-center justify-between text-[12px] font-semibold">
                        <span class="text-[#f0b400]">★4.6</span>
                        <span class="text-[#7B61FF]">Ongoing</span>
                    </div>

                    <a href="#"
                       class="mt-3 block rounded-xl bg-[#6f42f5] py-3 text-center text-[12px] font-semibold text-white shadow-[0_8px_16px_rgba(111,66,245,0.38)] transition hover:bg-[#6237ea]">
                        Baca Sekarang
                    </a>
                </div>

                <div class="rounded-[24px] border border-[#ddd3fb] bg-white p-5 shadow-[0_10px_24px_rgba(123,97,255,0.18)]">
                    <span class="inline-block rounded-full bg-[#efe7ff] px-3 py-1 text-[11px] font-semibold text-[#8b6cff]">
                        School
                    </span>

                    <h3 class="mt-3 text-[18px] font-extrabold leading-tight text-[#222222]">
                        School Senpai
                    </h3>

                    <p class="mt-1 text-[13px] leading-7 text-[#6d6d76]">
                        Cerita kehidupan sekolah yang penuh drama, persahabatan, dan cinta.
                    </p>

                    <div class="mt-5 flex items-center justify-between text-[12px] font-semibold">
                        <span class="text-[#f0b400]">★4.6</span>
                        <span class="text-[#7B61FF]">Ongoing</span>
                    </div>

                    <a href="#"
                       class="mt-3 block rounded-xl bg-[#6f42f5] py-3 text-center text-[12px] font-semibold text-white shadow-[0_8px_16px_rgba(111,66,245,0.38)] transition hover:bg-[#6237ea]">
                        Baca Sekarang
                    </a>
                </div>

                <div class="rounded-[24px] border border-[#ddd3fb] bg-white p-5 shadow-[0_10px_24px_rgba(123,97,255,0.18)]">
                    <span class="inline-block rounded-full bg-[#efe7ff] px-3 py-1 text-[11px] font-semibold text-[#8b6cff]">
                        School
                    </span>

                    <h3 class="mt-3 text-[18px] font-extrabold leading-tight text-[#222222]">
                        School Senpai
                    </h3>

                    <p class="mt-1 text-[13px] leading-7 text-[#6d6d76]">
                        Cerita kehidupan sekolah yang penuh drama, persahabatan, dan cinta.
                    </p>

                    <div class="mt-5 flex items-center justify-between text-[12px] font-semibold">
                        <span class="text-[#f0b400]">★4.6</span>
                        <span class="text-[#7B61FF]">Ongoing</span>
                    </div>

                    <a href="#"
                       class="mt-3 block rounded-xl bg-[#6f42f5] py-3 text-center text-[12px] font-semibold text-white shadow-[0_8px_16px_rgba(111,66,245,0.38)] transition hover:bg-[#6237ea]">
                        Baca Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

</body>
</html>