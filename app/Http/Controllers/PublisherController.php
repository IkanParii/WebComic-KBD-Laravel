<?php

namespace App\Http\Controllers;

use App\Models\Cerita;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // 👈 Wajib tambahin ini buat ignore unique pas update

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
            // 👇 Tambahin unique:ceritas,judul di sini
            'judul' => 'required|string|max:255|unique:ceritas,judul',
            'tanggal_rilis' => 'required|date',
            'deskripsi_singkat' => 'required|string|max:1000', 
            'isi_cerita' => 'required|string', 
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id', 
        ], [
            // 👇 Pesan error custom biar user ngerti
            'judul.unique' => 'Judul cerita ini sudah ada yang pakai, coba judul lain brow!',
        ]);

        $cerita = Cerita::create([
            'user_id' => Auth::id(),
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
            // 👇 Pake Rule::unique() biar bisa di-ignore pas ngedit judul yang sama
            'judul' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('ceritas', 'judul')->ignore($id)
            ],
            'tanggal_rilis' => 'required|date',
            'deskripsi_singkat' => 'required|string|max:1000',
            'isi_cerita' => 'required|string', 
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ], [
            // 👇 Pesan error custom juga di sini
            'judul.unique' => 'Judul cerita ini sudah ada yang pakai, coba judul lain brow!',
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