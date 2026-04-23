<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cerita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Data buat di TABEL (Admin sengaja di-hide biar aman)
        $users = User::where('role', '!=', 'admin')->latest()->get();
        $ceritas = Cerita::latest()->get();
        
        // 2. Data logic buat di KARTU OVERVIEW 
        $totalSemuaUser = User::count(); 
        $totalSemuaCerita = Cerita::count();

        return view('admin.dashboard', compact(
            'users', 
            'ceritas', 
            'totalSemuaUser', 
            'totalSemuaCerita'
        ));
    }

    // --- FITUR HAPUS USER ---
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        // SECURITY TWEAK: Cegah Admin hapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Waduh, lo nggak bisa nendang diri lo sendiri brow!');
        }

        // SECURITY TWEAK: Cegah Admin hapus Admin lainnya
        if ($user->role === 'admin') {
            return back()->with('error', 'Sesama Admin dilarang saling sikut!');
        }

        $user->delete(); 
        return back()->with('success', 'User berhasil ditendang!');
    }

    // --- FITUR UPDATE USERNAME ---
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // SECURITY TWEAK: Cegah Admin ngedit profil sesama Admin
        if ($user->role === 'admin') {
            return back()->with('error', 'Dilarang ngedit data sesama Admin!');
        }

        // Validasi inputan biar aman 
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update nama user pake strip_tags biar aman dari script jahat
        $user->update([
            'name' => strip_tags($request->name),
        ]);

        return back()->with('success', 'Username berhasil diubah paksa!');
    }

    // --- FITUR HAPUS CERITA ---
    public function destroyCerita($id)
    {
        $cerita = Cerita::findOrFail($id);
        $cerita->delete();
        return back()->with('success', 'Komik berhasil dihapus!');
    }
}