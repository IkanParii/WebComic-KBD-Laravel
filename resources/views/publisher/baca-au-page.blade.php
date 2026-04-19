<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Komik - School Senpai</title>

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
<body class="bg-[#f5f5f7] text-[#222222]">

    <!-- Navbar -->
    <header class="border border-[#ddd7ef] bg-[#f8f8fb] shadow-sm">
        <div class="mx-auto flex max-w-[1200px] items-center justify-between px-4 py-3">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[11px] font-bold text-[#7B61FF] shadow">
                    AV
                </div>
                <span class="text-[15px] font-bold text-[#171717]">AuVerse</span>
            </div>

            <nav class="hidden items-center gap-10 md:flex">
                <a href="#" class="text-[15px] font-semibold text-[#222222] hover:text-[#7B61FF]">Home</a>
                <a href="#" class="text-[15px] font-semibold text-[#222222] hover:text-[#7B61FF]">Komik</a>
                <a href="#" class="text-[15px] font-semibold text-[#222222] hover:text-[#7B61FF]">Dashboard</a>
            </nav>

            <a href="#"
               class="rounded-[16px] border border-[#bbb6c8] bg-[#f8f8fb] px-6 py-2 text-[15px] font-semibold text-[#222222] transition hover:bg-white">
                Logout
            </a>
        </div>
    </header>

    <!-- Hero / Heading -->
    <section class="relative overflow-hidden bg-[linear-gradient(180deg,#f1edff_0%,#f5f5f7_100%)]">
        <div class="mx-auto max-w-[1200px] px-6 pb-8 pt-8">
            <div class="relative">
                <span class="inline-block rounded-full bg-[#e9e1ff] px-3 py-1 text-[11px] font-semibold text-[#8b6cff]">
                    Reader Mode
                </span>

                <h1 class="mt-3 text-[28px] font-extrabold leading-tight text-[#222222] md:text-[52px]">
                    School Senpai
                </h1>

                <p class="mt-2 max-w-[700px] text-[15px] leading-7 text-[#6f6f79] md:text-[18px]">
                    Jelajahi daftar komik dengan tampilan clean, modern, dan nuansa anime
                    comic yang cocok untuk user muda
                </p>

                <div class="absolute right-0 top-[-20px] hidden md:block">
                    <img
                        src="{{ asset('image/background.jpeg') }}"
                        alt="Character"
                        class="w-[180px] object-contain opacity-20 grayscale"
                    >
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="pb-10 pt-4">
        <div class="mx-auto grid max-w-[1200px] gap-8 px-6 lg:grid-cols-[180px_minmax(0,1fr)]">

            <!-- Sidebar Card -->
            <div class="rounded-[22px] border border-[#ddd3fb] bg-white p-5 shadow-[0_10px_24px_rgba(123,97,255,0.12)]">
                <span class="inline-block rounded-full bg-[#efe7ff] px-3 py-1 text-[11px] font-semibold text-[#8b6cff]">
                    Action
                </span>

                <h3 class="mt-5 text-[18px] font-extrabold leading-tight text-[#222222]">
                    School Senpai
                </h3>

                <p class="mt-6 text-[13px] leading-7 text-[#6d6d76]">
                    Cerita kehidupan sekolah yang penuh drama, persahabatan, dan cinta.
                </p>

                <button
                    type="button"
                    class="mt-8 inline-flex items-center gap-2 rounded-xl bg-[#6f42f5] px-4 py-2 text-[12px] font-semibold text-white shadow-[0_8px_16px_rgba(111,66,245,0.35)] transition hover:bg-[#6237ea]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                    </svg>
                    Tambah ke Favorit
                </button>
            </div>

            <!-- Reader Panel -->
            <div class="flex min-h-[420px] items-center justify-center rounded-[22px] border border-[#ddd3fb] bg-white shadow-[0_10px_24px_rgba(123,97,255,0.12)]">
                <h2 class="text-[24px] font-extrabold text-[#6f42f5] md:text-[40px]">
                    Panel 1
                </h2>
            </div>

        </div>
    </section>

</body>
</html>