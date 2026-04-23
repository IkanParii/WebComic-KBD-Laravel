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

    // --- SECURITY TWEAK: Cegah IDOR Hapus Data Orang Lain ---
    public function destroy($id)
    {
        // Pastiin cerita yang mau dihapus emang beneran milik publisher yang lagi login
        $cerita = Cerita::where('user_id', Auth::id())->findOrFail($id);
        
        $cerita->delete();
        
        return redirect()->route('publisher.index')->with('success', 'Cerita berhasil dihapus!');
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
            // Tambahin max biar database gak kepenuhan (DOS attack)
            'judul' => 'required|string|max:255',
            'tanggal_rilis' => 'required|date',
            'deskripsi_singkat' => 'required|string|max:1000', 
            'isi_cerita' => 'required|string', 
            // SECURITY TWEAK: Pastiin ID genre yang dikirim beneran ada di tabel genres
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id', 
        ]);

        $cerita = Cerita::create([
            'user_id' => Auth::id(),
            // strip_tags() buat jaga-jaga ngebuang script jahat (XSS) di level database
            'judul' => strip_tags($request->judul),
            'tanggal_rilis' => $request->tanggal_rilis,
            'deskripsi_singkat' => strip_tags($request->deskripsi_singkat),
            'isi_cerita' => $request->isi_cerita, 
        ]);

        $cerita->genres()->attach($request->genres);

        return redirect()->route('publisher.index')->with('success', 'Cerita berhasil dibuat!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        // Ini udah bener banget, pake Auth::id() buat cegah IDOR
        $cerita = Cerita::where('user_id', Auth::id())->findOrFail($id);
        $genres = Genre::all();
        $selectedGenres = $cerita->genres->pluck('id')->toArray();

        return view('publisher.edit', compact('cerita', 'genres', 'selectedGenres'));
    }

    // 5. UPDATE CERITA
    public function update(Request $request, $id)
    {
        // Ini juga udah bener
        $cerita = Cerita::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_rilis' => 'required|date',
            'deskripsi_singkat' => 'required|string|max:1000',
            'isi_cerita' => 'required|string', 
            // SECURITY TWEAK: Pastiin ID genre beneran ada
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $cerita->update([
            'judul' => strip_tags($request->judul),
            'tanggal_rilis' => $request->tanggal_rilis,
            'deskripsi_singkat' => strip_tags($request->deskripsi_singkat),
            'isi_cerita' => $request->isi_cerita,
        ]);

        $cerita->genres()->sync($request->genres);

        return redirect()->route('publisher.index')->with('success', 'Cerita berhasil diupdate!');
    }
}