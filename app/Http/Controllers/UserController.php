<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cerita; // <-- Wajib ada biar Laravel tau tabel cerita

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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Fungsi toggle() bawaan Laravel: 
        // Kalau belum ada -> ditambah. Kalau udah ada -> dihapus.
        $user->favorites()->toggle($id);

        return back()->with('success', 'Koleksi favorit lo berhasil diupdate!');
    }

    // 3. Fungsi buat nampilin halaman baca cerita (Reader Mode)
    public function baca($id)
    {
        // Cari cerita berdasarkan ID, sekalian bawa data genrenya
        $cerita = Cerita::with('genres')->findOrFail($id);
        
        return view('cerita.baca', compact('cerita'));
    }
}