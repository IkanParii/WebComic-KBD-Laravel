<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AuVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-[#f7f6fb] text-[#222222]">

<div class="flex min-h-screen relative">

    <aside class="fixed left-0 top-0 flex h-screen w-[260px] flex-col bg-[#7b4dff] px-6 py-8 text-white shadow-xl z-40">
        <div class="mb-12 flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-xl font-bold text-[#7b4dff]">
                AV
            </div>
            <div>
                <h2 class="text-xl font-bold leading-tight">AuVerse</h2>
                <p class="text-[11px] font-medium text-white/80">Admin Panel</p>
            </div>
        </div>

        <nav class="flex flex-col gap-2">
            <a href="#" class="rounded-xl bg-white/20 px-5 py-3 text-sm font-semibold text-white transition">
                Dashboard Admin
            </a>
            <a href="{{ route('home') }}" class="rounded-xl px-5 py-3 text-sm font-semibold text-white/70 transition hover:bg-white/10 hover:text-white">
                Back to Site
            </a>
        </nav>
    </aside>

    <main class="ml-[260px] flex-1 px-10 py-10">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-[#241b3d]">Overview</h1>
            <p class="mt-1 text-sm text-[#6f6f79]">Kelola User, AU, dan Publisher dengan cepat.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-600 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-600 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="flex items-center gap-6 rounded-3xl border border-[#e6dcff] bg-white p-6 shadow-sm">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#efe7ff] text-2xl text-[#7b4dff]">👥</div>
                <div>
                    <p class="text-sm font-semibold text-[#6f6f79]">Total Users</p>
                    <h3 class="text-3xl font-extrabold text-[#241b3d]">{{ $totalSemuaUser }}</h3>
                </div>
            </div>

            <div class="flex items-center gap-6 rounded-3xl border border-[#e6dcff] bg-white p-6 shadow-sm">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#efe7ff] text-2xl text-[#7b4dff]">📚</div>
                <div>
                    <p class="text-sm font-semibold text-[#6f6f79]">Total AU</p>
                    <h3 class="text-3xl font-extrabold text-[#241b3d]">{{ $totalSemuaCerita }}</h3>
                </div>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-2">
            
            <div class="flex max-h-[600px] flex-col rounded-3xl border border-[#e6dcff] bg-white p-8 shadow-sm">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-[#241b3d]">Daftar User</h2>
                </div>
                
                <div class="overflow-y-auto pr-2">
                    <table class="w-full text-left text-sm">
                        <thead class="sticky top-0 bg-white pb-3">
                            <tr class="border-b border-[#f1edff] text-[#6f6f79]">
                                <th class="pb-3 font-semibold">Nama & Email</th>
                                <th class="pb-3 font-semibold">Role</th>
                                <th class="pb-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="border-b border-[#f1edff] last:border-none hover:bg-[#fcfbff]">
                                <td class="py-4 pr-4">
                                    <p class="font-bold text-[#241b3d]">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </td>
                                <td class="py-4 pr-4">
                                    <span class="rounded-lg bg-[#efe7ff] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#7b4dff]">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <button type="button" onclick="bukaPopupEdit(`{{ route('admin.user.update', $user->id) }}`, `{{ $user->name }}`)" class="mr-3 font-bold text-[#7b4dff] transition hover:text-[#5c37bf]">Edit</button>
                                    
                                    <button type="button" onclick="bukaPopupHapus(`{{ route('admin.user.destroy', $user->id) }}`, `User: {{ $user->name }}`)" class="font-bold text-red-500 transition hover:text-red-700">Hapus</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-6 text-center text-gray-500">Belum ada user terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex max-h-[600px] flex-col rounded-3xl border border-[#e6dcff] bg-white p-8 shadow-sm">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-[#241b3d]">Daftar AU</h2>
                </div>

                <div class="overflow-y-auto pr-2">
                    <table class="w-full text-left text-sm">
                        <thead class="sticky top-0 bg-white pb-3">
                            <tr class="border-b border-[#f1edff] text-[#6f6f79]">
                                <th class="pb-3 font-semibold">Judul AU</th>
                                <th class="pb-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ceritas as $cerita)
                            <tr class="border-b border-[#f1edff] last:border-none hover:bg-[#fcfbff]">
                                <td class="py-4 pr-4">
                                    <p class="line-clamp-1 max-w-[250px] font-bold text-[#241b3d]">{{ $cerita->judul }}</p>
                                    <p class="text-xs text-gray-500">Dibuat: {{ $cerita->created_at->format('d M Y') }}</p>
                                </td>
                                <td class="py-4 text-right">
                                    <button type="button" onclick="bukaPopupHapus(`{{ route('admin.cerita.destroy', $cerita->id) }}`, `Komik: {{ $cerita->judul }}`)" class="font-bold text-red-500 transition hover:text-red-700">Hapus</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="py-6 text-center text-gray-500">Belum ada AU yang diunggah.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <div id="popupHapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#241b3d]/40 px-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="w-full max-w-md scale-95 transform rounded-3xl bg-white p-8 shadow-2xl transition-transform duration-300">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-3xl text-red-500">
                ⚠️
            </div>
            
            <h3 class="mb-2 text-center text-xl font-bold text-[#241b3d]">Yakin mau hapus?</h3>
            <p class="mb-8 text-center text-sm text-[#6f6f79]">
                Data <strong id="namaDataDihapus" class="text-[#7b4dff]"></strong> bakal hilang permanen dan nggak bisa dibalikin lagi.
            </p>

            <div class="flex gap-4">
                <button type="button" onclick="tutupPopupHapus()" class="w-full rounded-xl border border-gray-300 bg-white py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Batal
                </button>
                
                <form id="formHapus" method="POST" class="w-full m-0">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-xl bg-red-500 py-3 text-sm font-bold text-white transition hover:bg-red-600 shadow-md shadow-red-500/30">
                        Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="popupEdit" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#241b3d]/40 px-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="w-full max-w-md scale-95 transform rounded-3xl bg-white p-8 shadow-2xl transition-transform duration-300">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#efe7ff] text-3xl">
                ✏️
            </div>
            
            <h3 class="mb-2 text-center text-xl font-bold text-[#241b3d]">Edit Username</h3>
            <p class="mb-6 text-center text-sm text-[#6f6f79]">
                Ubah paksa nama user yang melanggar aturan.
            </p>

            <form id="formEdit" method="POST" class="m-0">
                @csrf 
                @method('PUT')
                
                <div class="mb-8">
                    <label for="inputNamaEdit" class="mb-2 block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" id="inputNamaEdit" name="name" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#7b4dff] focus:outline-none focus:ring-2 focus:ring-[#7b4dff]/20">
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="tutupPopupEdit()" class="w-full rounded-xl border border-gray-300 bg-white py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Batal
                    </button>
                    
                    <button type="submit" class="w-full rounded-xl bg-[#7b4dff] py-3 text-sm font-bold text-white transition hover:bg-[#5c37bf] shadow-md shadow-[#7b4dff]/30">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // JS BUAT POPUP HAPUS
    const popupHapus = document.getElementById('popupHapus');
    const formHapus = document.getElementById('formHapus');
    const namaData = document.getElementById('namaDataDihapus');

    function bukaPopupHapus(urlAction, namaItem) {
        formHapus.action = urlAction;
        namaData.innerText = namaItem;
        
        popupHapus.classList.remove('hidden');
        popupHapus.classList.add('flex');
        
        setTimeout(() => {
            popupHapus.children[0].classList.remove('scale-95');
            popupHapus.children[0].classList.add('scale-100');
        }, 10);
    }

    function tutupPopupHapus() {
        popupHapus.children[0].classList.remove('scale-100');
        popupHapus.children[0].classList.add('scale-95');
        
        setTimeout(() => {
            popupHapus.classList.add('hidden');
            popupHapus.classList.remove('flex');
        }, 200); 
    }

    // JS BUAT POPUP EDIT NAMA
    const popupEdit = document.getElementById('popupEdit');
    const formEdit = document.getElementById('formEdit');
    const inputNamaEdit = document.getElementById('inputNamaEdit');

    function bukaPopupEdit(urlAction, namaSekarang) {
        // Tembak URL formnya
        formEdit.action = urlAction;
        
        // Masukin nama sekarang ke dalem input biar gampang ngeditnya
        inputNamaEdit.value = namaSekarang;
        
        // Tunjukin popup
        popupEdit.classList.remove('hidden');
        popupEdit.classList.add('flex');
        
        setTimeout(() => {
            popupEdit.children[0].classList.remove('scale-95');
            popupEdit.children[0].classList.add('scale-100');
        }, 10);
    }

    function tutupPopupEdit() {
        popupEdit.children[0].classList.remove('scale-100');
        popupEdit.children[0].classList.add('scale-95');
        
        setTimeout(() => {
            popupEdit.classList.add('hidden');
            popupEdit.classList.remove('flex');
        }, 200); 
    }
</script>

</body>
</html>