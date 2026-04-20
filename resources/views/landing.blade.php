<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuStory - Home</title>

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
@php
    // ganti path gambar di sini saja
    $heroImage = asset('image/background.jpeg');
@endphp
<body class="bg-[#f8f8fb] text-[#1f1f1f]">

    <!-- Navbar -->
    <header class="sticky top-0 z-50 flex w-full items-center justify-between border-b border-[#e6dcff] bg-white/85 px-[6%] py-5 backdrop-blur-md">
        <div class="flex items-center gap-3 text-[1.1rem] font-bold">
            <div class="mb-0 flex h-12 w-12 items-center justify-center rounded-[18px] bg-white text-base font-bold text-[#7b4dff] shadow-[0_8px_20px_rgba(0,0,0,0.12)]">
                AV
            </div>
            <span>AuVerse</span>
        </div>

        <nav class="flex flex-wrap items-center gap-[18px]">
            <a href="#feature" class="font-semibold text-[#241b3d] no-underline">Features</a>
            <a href="#about" class="font-semibold text-[#241b3d] no-underline">About</a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border-2 border-[#e6dcff] bg-white px-5 py-3.5 font-bold text-[#7b4dff] transition duration-200">
                Login
            </a>
        </nav>
    </header>

    <!-- Hero -->
    <section id="home" class="relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-cover bg-center opacity-[0.14]" style="background-image: url('{{ $heroImage }}');"></div>
            <div class="absolute inset-0 bg-white/70"></div>
        </div>

        <div class="relative mx-auto max-w-[1200px] px-6 pb-24 pt-16 sm:px-10 md:pb-32 md:pt-20">
            <div class="max-w-[700px]">
                <span class="inline-flex rounded-full bg-white px-4 py-2 text-[14px] font-semibold text-[#7B61FF] shadow-[0_4px_12px_rgba(123,97,255,0.18)]">
                    Alternative Universe Platform
                </span>

                <h1 class="mt-8 max-w-[700px] text-[38px] font-extrabold leading-[1.25] tracking-[-0.02em] text-[#111111] md:text-[58px]">
                    Tempat terbaik untuk
                    <span class="text-[#7B61FF]">AU, anime, dan komunitas favoritmu</span>
                </h1>

                <p class="mt-6 max-w-[720px] text-[16px] font-medium leading-8 text-[#60606b] md:text-[19px]">
                    AuVerse hadir dengan desain modern, mudah diakses, dan cocok untuk
                    pengguna muda yang suka anime, manga, dan AU creative
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-5">
                    <a href="{{ route('register') }}"
                       class="rounded-[20px] bg-[#7B61FF] px-8 py-4 text-[16px] font-semibold text-white shadow-[0_8px_20px_rgba(123,97,255,0.35)] transition hover:scale-[1.02]">
                        Daftar Sekarang
                    </a>

                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Utama -->
    <section id="feature" class="px-4 py-16 md:py-20">
        <div class="mx-auto max-w-[1200px]">
            <div class="text-center">
                <h2 class="text-[32px] font-extrabold text-[#222222] md:text-[48px]">Fitur Utama</h2>
                <p class="mt-2 text-[15px] text-[#7b7b87] md:text-[20px]">
                    Semua yang kamu butuhkan di satu tempat
                </p>
            </div>

            <div class="mt-12 grid gap-5 md:grid-cols-3">
                <div class="rounded-[24px] border border-[#ddd3fb] bg-white px-6 py-7 shadow-[0_8px_18px_rgba(123,97,255,0.16)]">
                    <h3 class="text-[18px] font-bold text-[#1f1f1f]">Au Collection</h3>
                    <p class="mt-4 text-[14px] font-medium leading-7 text-[#5f5f69]">
                        Kelola daftar Au dan temukan judul favorit dengan mudah
                    </p>
                </div>

                <div class="rounded-[24px] border border-[#ddd3fb] bg-white px-6 py-7 shadow-[0_8px_18px_rgba(123,97,255,0.16)]">
                    <h3 class="text-[18px] font-bold text-[#1f1f1f]">Publisher Data</h3>
                    <p class="mt-4 text-[14px] font-medium leading-7 text-[#5f5f69]">
                        Lihat alur data publisher yang terhubung di platform
                    </p>
                </div>

                <div class="rounded-[24px] border border-[#ddd3fb] bg-white px-6 py-7 shadow-[0_8px_18px_rgba(123,97,255,0.16)]">
                    <h3 class="text-[18px] font-bold text-[#1f1f1f]">User Friendly</h3>
                    <p class="mt-4 text-[14px] font-medium leading-7 text-[#5f5f69]">
                        Desain dibuat nyaman untuk user baru maupun admin
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kenapa -->
    <section id="about" class="px-4 pb-24 pt-8 md:pb-28 md:pt-16">
        <div class="mx-auto max-w-[1200px]">
            <div class="text-center">
                <h2 class="text-[32px] font-extrabold text-[#222222] md:text-[48px]">Kenapa AuVerse?</h2>
                <p class="mt-2 text-[15px] text-[#7b7b87] md:text-[20px]">
                    Platform yang simple, fun, dan punya banyak cerita menarik.
                </p>
            </div>

            <div class="mt-12 grid gap-5 md:grid-cols-3">
                <div class="rounded-[24px] border border-[#ddd3fb] bg-white px-6 py-7 shadow-[0_8px_18px_rgba(123,97,255,0.16)]">
                    <h3 class="text-[18px] font-bold text-[#1f1f1f]">UI Menarik</h3>
                    <p class="mt-4 text-[14px] font-medium leading-7 text-[#5f5f69]">
                        Desain clean dengan sentuhan Au Creative yang bikin user nyaman dan tidak bingung saat eksplor
                    </p>
                </div>

                <div class="rounded-[24px] border border-[#ddd3fb] bg-white px-6 py-7 shadow-[0_8px_18px_rgba(123,97,255,0.16)]">
                    <h3 class="text-[18px] font-bold text-[#1f1f1f]">Privasi Terjaga</h3>
                    <p class="mt-4 text-[14px] font-medium leading-7 text-[#5f5f69]">
                        Privasi Anda adalah prioritas kami. Kami menjaga data pribadi Anda tetap aman, terlindungi, dan hanya digunakan untuk meningkatkan pengalaman di website ini.
                    </p>
                </div>

                <div class="rounded-[24px] border border-[#ddd3fb] bg-white px-6 py-7 shadow-[0_8px_18px_rgba(123,97,255,0.16)]">
                    <h3 class="text-[18px] font-bold text-[#1f1f1f]">Tema Fresh</h3>
                    <p class="mt-4 text-[14px] font-medium leading-7 text-[#5f5f69]">
                        Perpaduan warna ungu dan putih memberi kesan elegan, playful, dan tetap premium
                    </p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>