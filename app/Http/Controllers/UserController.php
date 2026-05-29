<?php

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cerita;

class UserController extends Controller
{
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $favoritCeritas = $user->favorites()->latest()->get();

        return view('user.dashboard', compact('user', 'favoritCeritas'));
    }

    public function toggleFavorite($id)
    {
        // findOrFail memastikan cerita ada sebelum diproses, cegah error 500
        $cerita = Cerita::findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $wasFavorited = $user->favorites()->where('cerita_id', $cerita->id)->exists();

        // toggle() otomatis tambah jika belum ada, hapus jika sudah ada
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

        return back()->with('success', 'Koleksi favorit berhasil diperbarui.');
    }

    public function baca($id)
    {
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
