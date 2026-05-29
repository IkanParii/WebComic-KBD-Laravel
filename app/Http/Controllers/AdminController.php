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
        // Sembunyikan sesama admin dari tabel user agar tidak bisa dihapus via UI
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

    public function destroyUser($id)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        $user = User::findOrFail($id);

        // Cegah admin hapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        // Cegah admin hapus sesama admin
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin tidak bisa menghapus sesama admin.');
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

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Cegah admin edit data sesama admin
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin tidak bisa mengedit data sesama admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // strip_tags mencegah XSS via input nama
        $user->update([
            'name' => strip_tags($request->name),
        ]);

        return back()->with('success', 'Username berhasil diubah.');
    }

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

        return back()->with('success', 'Cerita berhasil dihapus.');
    }
}
