<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cerita - AuStory</title>
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
                <h2 class="text-3xl font-bold text-gray-800">Edit Cerita</h2>
                <p class="text-gray-500 mt-1 text-sm">Update detail AU yang sudah kamu buat.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-[24px] p-8 shadow-sm">
            <form action="{{ route('publisher.update', $cerita->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Judul AU</label>
                        <input type="text" name="judul" value="{{ old('judul', $cerita->judul) }}" required class="w-full h-11 border border-gray-300 rounded-xl px-4 outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Tanggal Rilis</label>
                        <input type="date" name="tanggal_rilis" value="{{ old('tanggal_rilis', $cerita->tanggal_rilis) }}" required class="w-full h-11 border border-gray-300 rounded-xl px-4 outline-none focus:ring-2 focus:ring-blue-500 text-gray-600">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Genre AU (Bisa pilih lebih dari 1)</label>
                    <div class="grid grid-cols-4 gap-3 border border-gray-300 p-4 rounded-xl">
                        @foreach($genres as $genre)
                            <label class="flex items-center space-x-2 cursor-pointer text-sm">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ in_array($genre->id, old('genres', $selectedGenres)) ? 'checked' : '' }} class="text-blue-600 rounded focus:ring-blue-500">
                                <span class="text-gray-600">{{ $genre->nama_genre }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Sinopsis Singkat</label>
                    <textarea name="deskripsi_singkat" required rows="3" class="w-full border border-gray-300 rounded-xl p-4 outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('deskripsi_singkat', $cerita->deskripsi_singkat) }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Isi Cerita Lengkap</label>
                    <textarea name="isi_cerita" required rows="10" class="w-full border border-gray-300 rounded-xl p-4 outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('isi_cerita', $cerita->isi_cerita) }}</textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow-md hover:shadow-lg">
                    Update Cerita
                </button>
            </form>
        </div>
    </main>
</body>
</html>