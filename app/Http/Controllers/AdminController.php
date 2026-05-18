<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cerita;
use App\Models\ActivityLog;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data buat di TABEL (Admin sengaja di-hide biar aman)
        $users = User::where('role', '!=', 'admin')->latest()->get();
        $ceritas = Cerita::latest()->get();
        $selectedEvent = (string) $request->query('event', '');
        $eventOptions = ActivityLog::query()
            ->select('event')
            ->distinct()
            ->orderBy('event')
            ->pluck('event');

        $activityLogsQuery = ActivityLog::query()->latest();
        if ($selectedEvent !== '') {
            $activityLogsQuery->where('event', $selectedEvent);
        }
        $activityLogs = $activityLogsQuery->take(50)->get();
        
        // 2. Data logic buat di KARTU OVERVIEW 
        $totalSemuaUser = User::count(); 
        $totalSemuaCerita = Cerita::count();

        return view('admin.dashboard', compact(
            'users', 
            'ceritas', 
            'activityLogs',
            'eventOptions',
            'selectedEvent',
            'totalSemuaUser', 
            'totalSemuaCerita'
        ));
    }

    // --- FITUR HAPUS USER ---
    public function destroyUser($id)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        $user = User::findOrFail($id);

        // SECURITY TWEAK: Cegah Admin hapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Waduh, lo nggak bisa nendang diri lo sendiri brow!');
        }

        // SECURITY TWEAK: Cegah Admin hapus Admin lainnya
        if ($user->role === 'admin') {
            return back()->with('error', 'Sesama Admin dilarang saling sikut!');
        }

        $targetName = $user->name;
        $targetRole = $user->role;
        $targetEmail = $user->email;
        $user->delete(); 

        ActivityLogger::log(
            'admin_deleted_user',
            sprintf(
                'Admin %s menghapus akun %s (%s, %s).',
                $admin->name,
                $targetName,
                $targetRole,
                $targetEmail
            ),
            $admin,
            request()
        );

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
        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        $cerita = Cerita::findOrFail($id);
        $judul = $cerita->judul;
        $owner = $cerita->user?->name ?? 'Unknown';
        $cerita->delete();

        ActivityLogger::log(
            'admin_deleted_cerita',
            sprintf(
                'Admin %s menghapus cerita "%s" milik %s.',
                $admin->name,
                $judul,
                $owner
            ),
            $admin,
            request()
        );

        return back()->with('success', 'Komik berhasil dihapus!');
    }
}
