<?php

namespace App\Http\Controllers;

use App\Models\Cerita;
use App\Models\Genre;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PublisherController extends Controller
{
    public function index()
    {
        // Hanya tampilkan cerita milik publisher yang sedang login
        $ceritas = Cerita::where('user_id', Auth::id())->with('genres')->latest()->get();
        return view('publisher.index', compact('ceritas'));
    }

    public function destroy($id)
    {
        // Filter by user_id mencegah publisher hapus cerita milik orang lain (IDOR protection)
        $cerita = Cerita::where('user_id', Auth::id())->findOrFail($id);
        $judul = $cerita->judul;
        $cerita->delete();

        ActivityLogger::log(
            'cerita_deleted',
            sprintf('%s menghapus cerita: "%s".', Auth::user()->name, $judul),
            Auth::user(),
            request()
        );

        return redirect()->route('publisher.index')->with('success', 'Cerita berhasil dihapus!');
    }

    public function create()
    {
        $genres = Genre::all();
        return view('publisher.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255|unique:ceritas,judul',
            'tanggal_rilis' => 'required|date',
            'deskripsi_singkat' => 'required|string|max:1000',
            'isi_cerita' => 'required|string',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ], [
            'judul.unique' => 'Judul cerita ini sudah digunakan, coba judul lain.',
        ]);

        $cerita = Cerita::create([
            'user_id' => Auth::id(),
            'judul' => strip_tags($request->judul),
            'tanggal_rilis' => $request->tanggal_rilis,
            'deskripsi_singkat' => strip_tags($request->deskripsi_singkat),
            'isi_cerita' => $request->isi_cerita,
        ]);

        $cerita->genres()->attach($request->genres);

        ActivityLogger::log(
            'cerita_created',
            sprintf('%s menambahkan cerita baru: "%s".', Auth::user()->name, $cerita->judul),
            Auth::user(),
            $request
        );

        return redirect()->route('publisher.index')->with('success', 'Cerita berhasil dibuat!');
    }

    public function edit($id)
    {
        // Filter by user_id mencegah publisher edit cerita milik orang lain (IDOR protection)
        $cerita = Cerita::where('user_id', Auth::id())->findOrFail($id);
        $genres = Genre::all();
        $selectedGenres = $cerita->genres->pluck('id')->toArray();

        return view('publisher.edit', compact('cerita', 'genres', 'selectedGenres'));
    }

    public function update(Request $request, $id)
    {
        // Filter by user_id mencegah publisher update cerita milik orang lain (IDOR protection)
        $cerita = Cerita::where('user_id', Auth::id())->findOrFail($id);
        $judulSebelum = $cerita->judul;

        $request->validate([
            // Rule::unique()->ignore() agar judul yang sama tidak dianggap duplikat saat edit
            'judul' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ceritas', 'judul')->ignore($id),
            ],
            'tanggal_rilis' => 'required|date',
            'deskripsi_singkat' => 'required|string|max:1000',
            'isi_cerita' => 'required|string',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ], [
            'judul.unique' => 'Judul cerita ini sudah digunakan, coba judul lain.',
        ]);

        $cerita->update([
            'judul' => strip_tags($request->judul),
            'tanggal_rilis' => $request->tanggal_rilis,
            'deskripsi_singkat' => strip_tags($request->deskripsi_singkat),
            'isi_cerita' => $request->isi_cerita,
        ]);

        $cerita->genres()->sync($request->genres);

        ActivityLogger::log(
            'cerita_updated',
            sprintf(
                '%s mengedit cerita "%s" menjadi "%s".',
                Auth::user()->name,
                $judulSebelum,
                $cerita->judul
            ),
            Auth::user(),
            $request
        );

        return redirect()->route('publisher.index')->with('success', 'Cerita berhasil diupdate!');
    }
}
