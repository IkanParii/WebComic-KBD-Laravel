<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnimeVerse - Landing Page</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen overflow-x-hidden bg-[radial-gradient(circle_at_top_left,_#f4efff_0%,_#ffffff_35%),linear-gradient(135deg,_#ffffff_0%,_#f2ebff_100%)] text-[#241b3d]">

    <header class="sticky top-0 z-50 flex w-full items-center justify-between border-b border-[#e6dcff] bg-white/85 px-[6%] py-5 backdrop-blur-md">
        <div class="flex items-center gap-3 text-[1.1rem] font-bold">
            <div class="mb-0 flex h-12 w-12 items-center justify-center rounded-[18px] bg-white text-base font-bold text-[#7b4dff] shadow-[0_8px_20px_rgba(0,0,0,0.12)]">
                AV
            </div>
            <span>AnimeVerse</span>
        </div>

        <nav class="flex flex-wrap items-center gap-[18px]">
            <a href="#home" class="font-semibold text-[#241b3d] no-underline">Home</a>
            <a href="#about" class="font-semibold text-[#241b3d] no-underline">About</a>
            <a href="#features" class="font-semibold text-[#241b3d] no-underline">Features</a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border-2 border-[#e6dcff] bg-white px-5 py-3.5 font-bold text-[#7b4dff] transition duration-200">
                Login
            </a>
        </nav>
    </header>

    <section id="home" class="grid min-h-[calc(100vh-90px)] grid-cols-1 items-center gap-[30px] px-[6%] py-[60px] lg:grid-cols-[1.1fr_0.9fr]">
        <div>
            <span class="inline-block rounded-full bg-[#efe8ff] px-4 py-2.5 font-bold text-[#5b33d6]">
                ✨ Comic Anime Platform
            </span>

            <h1 class="my-[18px] text-[2.2rem] font-bold leading-tight lg:text-[3rem]">
                Tempat terbaik untuk
                <span class="text-[#7b4dff]">komik, anime, dan komunitas favoritmu</span>
            </h1>

            <p class="mb-6 max-w-[600px] leading-8 text-[#6e6a7c]">
                AnimeVerse hadir dengan desain modern, mudah dipakai, dan cocok untuk
                pengguna muda yang suka anime, manga, dan comic style interface.
            </p>

            <div class="flex flex-wrap gap-3.5">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-br from-[#7b4dff] to-[#5b33d6] px-5 py-3.5 font-bold text-white shadow-[0_10px_22px_rgba(123,77,255,0.25)] transition duration-200 hover:-translate-y-0.5">
                    Mulai Sekarang
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center rounded-2xl border-2 border-[#e6dcff] bg-white px-5 py-3.5 font-bold text-[#7b4dff] transition duration-200">
                    Login
                </a>
            </div>
        </div>

        <div class="relative flex min-h-[400px] items-center justify-center">
            <div class="w-[300px] rounded-[28px] border-[3px] border-white/50 bg-gradient-to-br from-[#7b4dff] to-[#9f7cff] p-[30px] text-white shadow-[0_18px_45px_rgba(123,77,255,0.18)]">
                <h3 class="mb-3 text-2xl font-bold">🔥 Trending Comic</h3>
                <p>Attack, Fantasy, School, Romance, Action</p>
            </div>

            <div class="absolute left-5 top-10 rotate-[-10deg] rounded-full border-[3px] border-[#7b4dff] bg-white px-4 py-2.5 font-bold text-[#5b33d6] shadow-[5px_5px_0_rgba(123,77,255,0.12)]">
                SUGOI!
            </div>
            <div class="absolute right-2.5 top-[70px] rotate-[10deg] rounded-full border-[3px] border-[#7b4dff] bg-white px-4 py-2.5 font-bold text-[#5b33d6] shadow-[5px_5px_0_rgba(123,77,255,0.12)]">
                NEW EPISODE
            </div>
            <div class="absolute bottom-10 left-[60px] rotate-[-6deg] rounded-full border-[3px] border-[#7b4dff] bg-white px-4 py-2.5 font-bold text-[#5b33d6] shadow-[5px_5px_0_rgba(123,77,255,0.12)]">
                MANGA TIME
            </div>
        </div>
    </section>

    <section id="about" class="px-[6%] py-20">
        <div class="mb-10 text-center">
            <h2 class="mb-2.5 text-[2.2rem] font-bold">Kenapa AnimeVerse?</h2>
            <p class="text-[#6e6a7c]">Platform yang simple, fun, dan punya vibe anime banget.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-3xl border-2 border-[#e6dcff] bg-white p-7 shadow-[0_18px_45px_rgba(123,77,255,0.18)]">
                <h3 class="mb-3 text-xl font-bold">🎴 UI Menarik</h3>
                <p class="leading-7 text-[#6e6a7c]">
                    Desain clean dengan sentuhan comic anime yang bikin user nyaman dan
                    tidak bingung saat eksplor.
                </p>
            </div>

            <div class="rounded-3xl border-2 border-[#e6dcff] bg-white p-7 shadow-[0_18px_45px_rgba(123,77,255,0.18)]">
                <h3 class="mb-3 text-xl font-bold">⚡ Mudah Digunakan</h3>
                <p class="leading-7 text-[#6e6a7c]">
                    Navigasi sederhana dan cocok untuk user usia 17–21 yang suka tampilan
                    modern tapi tetap ringan.
                </p>
            </div>

            <div class="rounded-3xl border-2 border-[#e6dcff] bg-white p-7 shadow-[0_18px_45px_rgba(123,77,255,0.18)]">
                <h3 class="mb-3 text-xl font-bold">💜 Tema Fresh</h3>
                <p class="leading-7 text-[#6e6a7c]">
                    Perpaduan warna ungu dan putih memberi kesan elegan, playful, dan
                    tetap premium.
                </p>
            </div>
        </div>
    </section>

    <section id="features" class="px-[6%] py-20">
        <div class="mb-10 text-center">
            <h2 class="mb-2.5 text-[2.2rem] font-bold">Fitur Utama</h2>
            <p class="text-[#6e6a7c]">Semua yang kamu butuhkan di satu tempat.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-3xl border-2 border-[#e6dcff] bg-white p-7 shadow-[0_18px_45px_rgba(123,77,255,0.18)]">
                <span class="mb-4 inline-block text-[2rem]">📚</span>
                <h3 class="mb-3 text-xl font-bold">Comic Collection</h3>
                <p class="leading-7 text-[#6e6a7c]">Kelola daftar comic dan temukan judul favorit dengan mudah.</p>
            </div>

            <div class="rounded-3xl border-2 border-[#e6dcff] bg-white p-7 shadow-[0_18px_45px_rgba(123,77,255,0.18)]">
                <span class="mb-4 inline-block text-[2rem]">🏢</span>
                <h3 class="mb-3 text-xl font-bold">Publisher Data</h3>
                <p class="leading-7 text-[#6e6a7c]">Lihat dan atur data publisher yang terhubung di platform.</p>
            </div>

            <div class="rounded-3xl border-2 border-[#e6dcff] bg-white p-7 shadow-[0_18px_45px_rgba(123,77,255,0.18)]">
                <span class="mb-4 inline-block text-[2rem]">👤</span>
                <h3 class="mb-3 text-xl font-bold">User Friendly</h3>
                <p class="leading-7 text-[#6e6a7c]">Desain dibuat nyaman untuk user baru maupun admin.</p>
            </div>
        </div>
    </section>

    <footer class="px-6 py-6 text-center text-[#6e6a7c]">
        <p>© {{ date('Y') }} AnimeVerse. All rights reserved.</p>
    </footer>

</body>
</html>