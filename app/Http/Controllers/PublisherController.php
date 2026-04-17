<?php

namespace App\Http\Controllers;

use App\Models\Cerita;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublisherController extends Controller
{
    // 1. DAFTAR CERITA
    public function index()
    {
        $ceritas = Cerita::where('user_id', Auth::id())->with('genres')->latest()->get();
        return view('publisher.index', compact('ceritas'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        $genres = Genre::all();
        return view('publisher.create', compact('genres'));
    }

    // 3. SIMPAN CERITA BARU
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_rilis' => 'required|date',
            'deskripsi_singkat' => 'required|string',
            'isi_cerita' => 'required|string', // Validasi isi cerita
            'genres' => 'required|array',
        ]);

        $cerita = Cerita::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'tanggal_rilis' => $request->tanggal_rilis,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'isi_cerita' => $request->isi_cerita, // Simpan isi cerita
        ]);

        $cerita->genres()->attach($request->genres);

        return redirect()->route('publisher.index')->with('success', 'Cerita berhasil dibuat!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $cerita = Cerita::where('user_id', Auth::id())->findOrFail($id);
        $genres = Genre::all();
        $selectedGenres = $cerita->genres->pluck('id')->toArray();

        return view('publisher.edit', compact('cerita', 'genres', 'selectedGenres'));
    }

    // 5. UPDATE CERITA
    public function update(Request $request, $id)
    {
        $cerita = Cerita::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_rilis' => 'required|date',
            'deskripsi_singkat' => 'required|string',
            'isi_cerita' => 'required|string', // Validasi isi cerita
            'genres' => 'required|array',
        ]);

        $cerita->update([
            'judul' => $request->judul,
            'tanggal_rilis' => $request->tanggal_rilis,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'isi_cerita' => $request->isi_cerita, // Update isi cerita
        ]);

        $cerita->genres()->sync($request->genres);

        return redirect()->route('publisher.index')->with('success', 'Cerita berhasil diupdate!');
    }
}