<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Backup Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <main class="mx-auto max-w-2xl px-6 py-10">
        <h1 class="text-2xl font-bold">Secret Database Backup & Restore</h1>
        <p class="mt-2 text-sm text-slate-300">
            Akses ini tidak ditampilkan di menu. Gunakan dengan hati-hati.
        </p>

        @if (session('success'))
            <div class="mt-4 rounded border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-rose-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (! $isVerified)
            <section class="mt-8 rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <h2 class="text-lg font-semibold">Verifikasi Manual</h2>
                <div class="mt-4 space-y-3 text-sm">
                    <p>OTP sudah dikirim ke email admin yang sedang login.</p>
                    <form method="POST" action="{{ route('admin.secret.backup.resend-otp') }}">
                        @csrf
                        <button type="submit" class="rounded bg-sky-500 px-3 py-2 font-semibold text-slate-950 hover:bg-sky-400">
                            Kirim Ulang OTP ke Email
                        </button>
                    </form>
                    <p>
                        Captcha:
                        <span class="rounded bg-slate-800 px-2 py-1 font-mono text-base">{{ $captchaQuestion }} = ?</span>
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.secret.backup.verify') }}" class="mt-5 space-y-4">
                    @csrf
                    <input type="password" name="panel_password" placeholder="Password panel" class="w-full rounded border border-slate-700 bg-slate-900 px-3 py-2" required>
                    <input type="text" name="otp" placeholder="OTP 6 digit" class="w-full rounded border border-slate-700 bg-slate-900 px-3 py-2" required>
                    <input type="text" name="captcha" placeholder="Jawaban captcha" class="w-full rounded border border-slate-700 bg-slate-900 px-3 py-2" required>
                    <button type="submit" class="rounded bg-emerald-500 px-4 py-2 font-semibold text-slate-950 hover:bg-emerald-400">
                        Verifikasi & Masuk Panel
                    </button>
                </form>
            </section>
        @else
            <section class="mt-8 rounded-lg border border-emerald-500/40 bg-emerald-500/10 p-6 text-emerald-200">
                Verifikasi aktif sampai {{ $verifiedUntil }}.
            </section>

            <section class="mt-6 rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <h2 class="text-lg font-semibold">Backup Database</h2>
                <form method="POST" action="{{ route('admin.secret.backup.store') }}" class="mt-4 space-y-4">
                    @csrf
                    <button type="submit" class="rounded bg-emerald-500 px-4 py-2 font-semibold text-slate-950 hover:bg-emerald-400">
                        Jalankan Backup
                    </button>
                </form>
            </section>

            <section class="mt-6 rounded-lg border border-slate-800 bg-slate-900/70 p-6">
                <h2 class="text-lg font-semibold">Restore Database</h2>
                <p class="mt-2 text-sm text-amber-200">Restore akan menimpa data database aktif.</p>
                <form method="POST" action="{{ route('admin.secret.backup.restore') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf
                    <input type="file" name="backup_file" accept=".sql" class="w-full rounded border border-slate-700 bg-slate-900 px-3 py-2" required>
                    <button type="submit" class="rounded bg-rose-500 px-4 py-2 font-semibold text-slate-50 hover:bg-rose-400">
                        Jalankan Restore
                    </button>
                </form>
            </section>
        @endif
    </main>
</body>
</html>
