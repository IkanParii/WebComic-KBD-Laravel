<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Cerita - AuVerse</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-[#fcfcfd] flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#7B4DFF] text-white flex flex-col pt-8">
        <div class="px-6 flex items-center gap-3 mb-12">
            <div class="w-10 h-10 bg-white rounded-full flex justify-center items-center text-[#7B4DFF] font-bold">
                AV
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">AuVerse</h1>
                <p class="text-[10px] text-[#e6dcff]">Publisher Panel</p>
            </div>
        </div>

        <nav class="flex flex-col gap-3 px-4">
            <a href="{{ route('publisher.create') }}" class="px-4 py-3 rounded-xl font-medium text-sm hover:bg-white/20 transition">
                Tambah Cerita
            </a>

            <a href="{{ route('publisher.index') }}" class="bg-white/20 px-4 py-3 rounded-xl font-medium text-sm hover:bg-white/30 transition">
                Daftar Cerita
            </a>

            <a href="{{ url('/home') }}" class="px-4 py-3 rounded-xl font-medium text-sm hover:bg-white/20 transition mt-4">
                Back to Site
            </a>
        </nav>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-10">

        <!-- HEADER -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Daftar Cerita Anda</h2>
                <p class="text-gray-500 mt-1 text-sm">Kelola semua cerita AU yang telah Anda publikasikan.</p>
            </div>

            <a href="{{ route('publisher.create') }}"
               class="bg-[#7B4DFF] text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-[#6938f0] transition shadow-sm hover:shadow">
                + Tambah Baru
            </a>
        </div>

        <!-- SUCCESS -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl text-sm font-medium border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <!-- TABLE -->
        <div class="bg-white border border-gray-200 rounded-[24px] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">

                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="p-5 text-sm font-bold text-gray-600">Judul Cerita</th>
                            <th class="p-5 text-sm font-bold text-gray-600">Genre</th>
                            <th class="p-5 text-sm font-bold text-gray-600">Tanggal Rilis</th>
                            <th class="p-5 text-sm font-bold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ceritas as $cerita)

                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">

                            <td class="p-5">
                                <p class="font-bold text-gray-800 text-base">{{ $cerita->judul }}</p>
                                <p class="text-xs text-gray-400 mt-1 line-clamp-1 max-w-xs">
                                    {{ Str::limit($cerita->deskripsi_singkat, 60) }}
                                </p>
                            </td>

                            <td class="p-5">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($cerita->genres as $genre)
                                        <span class="bg-[#efe7ff] border border-[#e0d4ff] text-[#7B4DFF] text-[11px] px-2.5 py-1 rounded-md font-medium">
                                            {{ $genre->nama_genre }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <td class="p-5 text-sm text-gray-600 font-medium">
                                {{ \Carbon\Carbon::parse($cerita->tanggal_rilis)->format('d M Y') }}
                            </td>

                            <td class="p-5 text-center">
                                <a href="{{ route('publisher.edit', $cerita->id) }}"
                                   class="inline-block px-4 py-2 bg-[#efe7ff] text-[#7B4DFF] rounded-lg text-sm font-semibold hover:bg-[#e0d4ff] transition">
                                    Edit
                                </a>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="4" class="p-10 text-center text-gray-400">
                                <p class="font-medium text-gray-500">Belum ada cerita yang dibuat.</p>
                            </td>
                        </tr>

                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </main>
</body>
</html>