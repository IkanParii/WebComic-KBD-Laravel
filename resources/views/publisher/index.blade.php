<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Cerita - AuStory</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-[#fcfcfd] flex min-h-screen">

    <aside class="w-64 bg-[#7e57c2] text-white flex flex-col pt-8">
        <div class="px-6 flex items-center gap-3 mb-12">
            <div class="w-10 h-10 bg-white rounded-full flex justify-center items-center text-purple-600 font-bold">AS</div>
            <div>
                <h1 class="font-bold text-lg leading-tight">AuStory</h1>
                <p class="text-[10px] text-purple-200">Admin Panel</p>
            </div>
        </div>
        <nav class="flex flex-col gap-3 px-4">
            <a href="{{ route('publisher.create') }}" class="px-4 py-3 rounded-xl font-medium text-sm transition hover:bg-white/20">Tambah Cerita</a>
            <a href="{{ route('publisher.index') }}" class="bg-white/20 px-4 py-3 rounded-xl font-medium text-sm transition hover:bg-white/30">Daftar Cerita</a>
            <a href="{{ url('/') }}" class="px-4 py-3 rounded-xl font-medium text-sm transition hover:bg-white/20 mt-4">Back to Site</a>
        </nav>
    </aside>

    <main class="flex-1 p-10">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Daftar Cerita Anda</h2>
                <p class="text-gray-500 mt-1 text-sm">Kelola semua cerita AU yang telah Anda publikasikan.</p>
            </div>
            <a href="{{ route('publisher.create') }}" class="bg-[#7e57c2] text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-purple-600 transition shadow-sm hover:shadow">
                + Tambah Baru
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl text-sm font-medium border border-green-200">
                {{ session('success') }}
            </div>
        @endif

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
                                        <span class="bg-purple-50 border border-purple-100 text-purple-600 text-[11px] px-2.5 py-1 rounded-md font-medium">
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
                                   class="inline-block px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-semibold hover:bg-blue-100 transition">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <p class="font-medium text-gray-500">Belum ada cerita yang dibuat.</p>
                                </div>
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