<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk auto-logout user setelah tidak ada aktivitas
 * dalam jangka waktu yang dikonfigurasi via SESSION_INACTIVITY_TIMEOUT di .env.
 *
 * Cara kerja:
 * - Setiap request yang masuk dari user yang sudah login,
 *   middleware ini mengecek apakah waktu sejak aktivitas terakhir
 *   sudah melebihi batas timeout.
 * - Jika iya, user di-logout dan di-redirect ke halaman login.
 * - Jika tidak, timestamp 'last_activity' di session diperbarui.
 */
class InactivityLogout
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $timeoutMinutes = (int) config('session.inactivity_timeout', env('SESSION_INACTIVITY_TIMEOUT', 30));
        $timeoutSeconds = $timeoutMinutes * 60;

        $lastActivity = $request->session()->get('last_activity');

        if ($lastActivity !== null && (time() - (int) $lastActivity) > $timeoutSeconds) {
            // Simpan pesan sebelum logout agar bisa ditampilkan setelah redirect
            $request->session()->flush();
            Auth::logout();

            return redirect()->route('login')
                ->with('status', 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan login kembali.');
        }

        // Perbarui timestamp aktivitas terakhir
        $request->session()->put('last_activity', time());

        return $next($request);
    }
}
