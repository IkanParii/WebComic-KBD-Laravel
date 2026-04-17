<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cerita;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Data buat di TABEL (Admin sengaja di-hide biar aman)
        $users = User::where('role', '!=', 'admin')->latest()->get();
        $ceritas = Cerita::latest()->get();
        
        // 2. Data logic buat di KARTU OVERVIEW (Ngitung pure semua total isi tabel database)
        $totalSemuaUser = User::count(); 
        $totalSemuaCerita = Cerita::count();

        // Lempar semuanya ke view
        return view('admin.dashboard', compact(
            'users', 
            'ceritas', 
            'totalSemuaUser', 
            'totalSemuaCerita'
        ));
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); 
        return back()->with('success', 'User berhasil ditendang!');
    }

    public function destroyCerita($id)
    {
        $cerita = Cerita::findOrFail($id);
        $cerita->delete();
        return back()->with('success', 'Komik berhasil dihapus!');
    }
}