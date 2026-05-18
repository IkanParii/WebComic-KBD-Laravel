<?php

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cerita; 

class UserController extends Controller
{
    // 1. Fungsi buat nampilin halaman Dashboard User
    public function dashboard()
    {
        // Ambil data user yang lagi login
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Tarik semua cerita yang udah di-favoritin sama user ini
        $favoritCeritas = $user->favorites()->latest()->get();

        // Lempar datanya ke file tampilan (view)
        return view('user.dashboard', compact('user', 'favoritCeritas'));
    }

    // 2. Fungsi buat nambah/ngapus favorit (Toggle)
    public function toggleFavorite($id)
    {
        // --- SECURITY TWEAK ---
        // Cek dulu, ceritanya beneran ada nggak di database?
        // Kalau ngga ada, otomatis dilempar ke halaman 404 (aman dari error 500)
        $cerita = Cerita::findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $wasFavorited = $user->favorites()->where('cerita_id', $cerita->id)->exists();
        
        // Fungsi toggle() bawaan Laravel: 
        // Kalau belum ada -> ditambah. Kalau udah ada -> dihapus.
        $user->favorites()->toggle($cerita->id);

        ActivityLogger::log(
            $wasFavorited ? 'favorite_removed' : 'favorite_added',
            sprintf(
                '%s %s cerita favorit: "%s".',
                $user->name,
                $wasFavorited ? 'menghapus' : 'menambahkan',
                $cerita->judul
            ),
            $user,
            request()
        );

        return back()->with('success', 'Koleksi favorit lo berhasil diupdate!');
    }

    // 3. Fungsi buat nampilin halaman baca cerita (Reader Mode)
    public function baca($id)
    {
        // Cari cerita berdasarkan ID, sekalian bawa data genrenya
        $cerita = Cerita::with('genres')->findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        ActivityLogger::log(
            'cerita_read',
            sprintf('%s membaca cerita: "%s".', $user->name, $cerita->judul),
            $user,
            request()
        );
        
        return view('cerita.baca', compact('cerita'));
    }
}
