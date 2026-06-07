<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Backup Panel · KBD Admin</title>
    <meta name="description" content="Panel backup dan restore database rahasia untuk admin KBD WebComic.">
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

        :root {
            --glass-bg: rgba(15, 23, 42, 0.7);
            --glass-border: rgba(99, 102, 241, 0.15);
            --glow-purple: rgba(139, 92, 246, 0.3);
            --glow-emerald: rgba(16, 185, 129, 0.3);
            --glow-rose: rgba(244, 63, 94, 0.3);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #020817;
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse at 20% 0%, rgba(139, 92, 246, 0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 100%, rgba(16, 185, 129, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(30, 41, 59, 0.5) 0%, transparent 80%);
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.05);
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .mono { font-family: 'JetBrains Mono', monospace; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45); }

        .btn-emerald {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
        }
        .btn-emerald:hover { box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4); }

        .btn-rose {
            background: linear-gradient(135deg, #e11d48, #f43f5e);
            color: white;
            box-shadow: 0 4px 15px rgba(244, 63, 94, 0.25);
        }
        .btn-rose:hover { box-shadow: 0 6px 20px rgba(244, 63, 94, 0.4); }

        .btn-ghost {
            background: rgba(255,255,255,0.05);
            color: #94a3b8;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); color: #e2e8f0; }

        .btn-sky {
            background: linear-gradient(135deg, #0284c7, #0ea5e9);
            color: white;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.25);
        }
        .btn-sky:hover { box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4); }

        .btn-amber {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            color: #1c1917;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.25);
        }
        .btn-amber:hover { box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4); }

        .btn-sm { padding: 5px 11px; font-size: 12px; }

        .input-field {
            width: 100%;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 8px;
            padding: 10px 14px;
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .input-field:focus {
            border-color: rgba(99, 102, 241, 0.6);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .input-field::placeholder { color: #475569; }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }
        .section-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .table-row {
            display: grid;
            grid-template-columns: 1fr auto auto auto auto;
            gap: 12px;
            align-items: center;
            padding: 12px 16px;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .table-row:hover { background: rgba(99, 102, 241, 0.05); }
        .table-row + .table-row { border-top: 1px solid rgba(255,255,255,0.04); }

        .alert-banner {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .pulse-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse 2s infinite;
            flex-shrink: 0;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }

        .shimmer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.3), transparent);
            margin: 8px 0;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.75);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: #0f172a;
            border: 1px solid rgba(244,63,94,0.3);
            border-radius: 16px;
            padding: 28px;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(244,63,94,0.1);
            animation: modalIn 0.2s ease;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .select-field {
            width: 100%;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 8px;
            padding: 10px 14px;
            color: #e2e8f0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 36px;
        }
        .select-field:focus {
            border-color: rgba(99, 102, 241, 0.6);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .select-field option { background: #0f172a; }
    </style>
</head>
<body>
    <div class="max-w-4xl mx-auto px-4 py-10">

        {{-- ===== HEADER ===== --}}
        <div style="margin-bottom: 32px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 20px rgba(99,102,241,0.4);">
                    🛡️
                </div>
                <div>
                    <h1 style="font-size: 22px; font-weight: 700; color: #f1f5f9; margin: 0;">Secret Backup Panel</h1>
                    <p style="font-size: 12px; color: #475569; margin: 2px 0 0;">Akses terbatas · Tidak ditampilkan di menu publik</p>
                </div>
            </div>
            <div class="shimmer-divider" style="margin-top: 16px;"></div>
        </div>

        {{-- ===== ALERT MESSAGES ===== --}}
        @if (session('success'))
            <div class="alert-banner" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.25); color: #6ee7b7; margin-bottom: 20px;">
                <span style="font-size: 16px; flex-shrink: 0;">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-banner" style="background: rgba(244,63,94,0.08); border: 1px solid rgba(244,63,94,0.25); color: #fda4af; margin-bottom: 20px;">
                <span style="font-size: 16px; flex-shrink: 0;">❌</span>
                <ul style="margin: 0; padding-left: 16px; list-style-type: disc;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ===== BELUM VERIFIED ===== --}}
        @if (! $isVerified)
            <div class="glass-card" style="padding: 28px; border-color: rgba(245,158,11,0.2);">
                <div class="section-header">
                    <div class="section-icon" style="background: rgba(245,158,11,0.1);">🔐</div>
                    <div>
                        <h2 style="font-size: 16px; font-weight: 600; color: #f1f5f9; margin: 0;">Verifikasi Manual Diperlukan</h2>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0;">OTP sudah dikirim ke email admin yang sedang login</p>
                    </div>
                </div>

                <div class="alert-banner" style="background: rgba(245,158,11,0.06); border: 1px solid rgba(245,158,11,0.15); color: #fcd34d; margin-bottom: 20px;">
                    <span style="flex-shrink: 0;">💡</span>
                    <div>
                        Captcha: <span class="mono" style="background: rgba(0,0,0,0.3); padding: 2px 10px; border-radius: 6px; font-size: 15px; color: #fde68a;">{{ $captchaQuestion }} = ?</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.secret.backup.resend-otp') }}" style="margin-bottom: 16px;">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="font-size: 12px;">
                        📨 Kirim Ulang OTP ke Email
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.secret.backup.verify') }}" style="display: flex; flex-direction: column; gap: 12px;" id="form-verify">
                    @csrf
                    <div>
                        <label style="display: block; font-size: 12px; color: #64748b; margin-bottom: 6px; font-weight: 500;">PASSWORD PANEL</label>
                        <input id="inp-panel-password" type="password" name="panel_password" placeholder="Masukkan password panel..." class="input-field" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; color: #64748b; margin-bottom: 6px; font-weight: 500;">OTP (6 DIGIT)</label>
                        <input id="inp-otp" type="text" name="otp" placeholder="000000" class="input-field mono" maxlength="6" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; color: #64748b; margin-bottom: 6px; font-weight: 500;">JAWABAN CAPTCHA</label>
                        <input id="inp-captcha" type="text" name="captcha" placeholder="Jawaban angka..." class="input-field" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="btn-verify">
                        🔓 Verifikasi &amp; Masuk Panel
                    </button>
                </form>
            </div>

        {{-- ===== SUDAH VERIFIED ===== --}}
        @else
            {{-- Status Badge --}}
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 24px; padding: 12px 16px; background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2); border-radius: 10px;">
                <div class="pulse-dot"></div>
                <div style="font-size: 13px; color: #6ee7b7;">
                    Sesi terverifikasi aktif sampai <span class="mono" style="color: #a7f3d0; font-size: 13px;">{{ $verifiedUntil }}</span>
                </div>
            </div>

            {{-- ======================== --}}
            {{-- SECTION: BUAT BACKUP --}}
            {{-- ======================== --}}
            <div class="glass-card" style="padding: 24px; margin-bottom: 20px;">
                <div class="section-header">
                    <div class="section-icon" style="background: rgba(16,185,129,0.1);">💾</div>
                    <div>
                        <h2 style="font-size: 15px; font-weight: 600; color: #f1f5f9; margin: 0;">Buat Backup Baru</h2>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0;">Backup akan disimpan di server dan muncul di daftar di bawah</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.secret.backup.store') }}" id="form-backup">
                    @csrf
                    <button type="submit" class="btn btn-emerald" id="btn-backup" onclick="this.disabled=true; this.innerHTML='⏳ Memproses...'; this.form.submit();">
                        💾 Jalankan Backup Sekarang
                    </button>
                </form>
            </div>

            {{-- ======================== --}}
            {{-- SECTION: DAFTAR BACKUP --}}
            {{-- ======================== --}}
            <div class="glass-card" style="padding: 24px; margin-bottom: 20px;">
                <div class="section-header" style="justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="section-icon" style="background: rgba(99,102,241,0.1);">📋</div>
                        <div>
                            <h2 style="font-size: 15px; font-weight: 600; color: #f1f5f9; margin: 0;">Daftar Backup di Server</h2>
                            <p style="font-size: 12px; color: #64748b; margin: 2px 0 0;">{{ count($backupFiles) }} file backup tersedia</p>
                        </div>
                    </div>
                    <span class="badge-pill" style="background: rgba(99,102,241,0.1); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.2);">
                        {{ count($backupFiles) }} files
                    </span>
                </div>

                @if (count($backupFiles) === 0)
                    <div style="text-align: center; padding: 36px 0; color: #475569;">
                        <div style="font-size: 40px; margin-bottom: 8px;">🗄️</div>
                        <p style="font-size: 14px;">Belum ada backup di server.</p>
                        <p style="font-size: 12px; color: #334155;">Klik "Buat Backup Baru" di atas untuk membuat backup pertama.</p>
                    </div>
                @else
                    {{-- Header tabel --}}
                    <div style="display: grid; grid-template-columns: 1fr 80px 120px 120px; gap: 12px; padding: 6px 16px; margin-bottom: 4px;">
                        <span style="font-size: 11px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Nama File</span>
                        <span style="font-size: 11px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Ukuran</span>
                        <span style="font-size: 11px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Tanggal</span>
                        <span style="font-size: 11px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; text-align: right;">Aksi</span>
                    </div>
                    <div class="shimmer-divider"></div>

                    @foreach ($backupFiles as $index => $file)
                        <div style="display: grid; grid-template-columns: 1fr 80px 120px 120px; gap: 12px; align-items: center; padding: 12px 16px; border-radius: 10px; transition: background 0.2s; {{ $loop->last ? '' : 'border-bottom: 1px solid rgba(255,255,255,0.04);' }}"
                             onmouseover="this.style.background='rgba(99,102,241,0.05)'"
                             onmouseout="this.style.background='transparent'">

                            {{-- Nama file --}}
                            <div style="min-width: 0;">
                                <div class="mono" style="font-size: 12.5px; color: #c7d2fe; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $file['name'] }}">
                                    {{ $file['name'] }}
                                </div>
                                @if ($loop->first)
                                    <span class="badge-pill" style="background: rgba(16,185,129,0.1); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.2); margin-top: 3px;">
                                        ✨ Terbaru
                                    </span>
                                @endif
                            </div>

                            {{-- Ukuran --}}
                            <span style="font-size: 12px; color: #94a3b8;">{{ $file['size_human'] }}</span>

                            {{-- Tanggal --}}
                            <span style="font-size: 11.5px; color: #64748b;">{{ $file['modified'] }}</span>

                            {{-- Aksi --}}
                            <div style="display: flex; gap: 6px; justify-content: flex-end; flex-wrap: wrap;">
                                {{-- Download --}}
                                <a href="{{ route('admin.secret.backup.download', ['filename' => $file['name']]) }}"
                                   class="btn btn-sky btn-sm"
                                   title="Download file backup ini">
                                    ⬇️
                                </a>

                                {{-- Restore --}}
                                <button type="button"
                                    class="btn btn-amber btn-sm"
                                    title="Restore dari file ini"
                                    onclick="openRestoreModal('{{ $file['name'] }}')">
                                    🔄
                                </button>

                                {{-- Hapus --}}
                                <button type="button"
                                    class="btn btn-rose btn-sm"
                                    title="Hapus file backup ini"
                                    onclick="openDeleteModal('{{ $file['name'] }}')">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- ======================== --}}
            {{-- SECTION: RESTORE DARI FILE SERVER (via select) --}}
            {{-- ======================== --}}
            @if (count($backupFiles) > 0)
            <div class="glass-card" style="padding: 24px; margin-bottom: 20px; border-color: rgba(245,158,11,0.15);">
                <div class="section-header">
                    <div class="section-icon" style="background: rgba(245,158,11,0.1);">🔄</div>
                    <div>
                        <h2 style="font-size: 15px; font-weight: 600; color: #f1f5f9; margin: 0;">Restore dari Backup Server</h2>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0;">Pilih file backup yang sudah ada di server</p>
                    </div>
                </div>

                <div class="alert-banner" style="background: rgba(245,158,11,0.06); border: 1px solid rgba(245,158,11,0.15); color: #fcd34d; margin-bottom: 16px; font-size: 12.5px;">
                    ⚠️ <span>Restore akan <strong>menimpa seluruh data database aktif</strong>. Pastikan backup yang dipilih sudah benar sebelum melanjutkan.</span>
                </div>

                <form method="POST" action="{{ route('admin.secret.backup.restore-server') }}" id="form-restore-server">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <label for="inp-server-backup-file" style="display: block; font-size: 12px; color: #64748b; margin-bottom: 6px; font-weight: 500;">PILIH FILE BACKUP</label>
                        <select name="server_backup_file" id="inp-server-backup-file" class="select-field" required>
                            <option value="" disabled selected>— Pilih file backup dari server —</option>
                            @foreach ($backupFiles as $file)
                                <option value="{{ $file['name'] }}">{{ $file['name'] }} ({{ $file['size_human'] }} · {{ $file['modified'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-amber" onclick="confirmRestoreServer()" id="btn-restore-server">
                        🔄 Jalankan Restore dari Server
                    </button>
                </form>
            </div>
            @endif

            {{-- ======================== --}}
            {{-- SECTION: UPLOAD DARI PC --}}
            {{-- ======================== --}}
            <div class="glass-card" style="padding: 24px; border-color: rgba(244,63,94,0.15);">
                <div class="section-header">
                    <div class="section-icon" style="background: rgba(244,63,94,0.1);">📤</div>
                    <div>
                        <h2 style="font-size: 15px; font-weight: 600; color: #f1f5f9; margin: 0;">Import &amp; Restore dari PC</h2>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0;">Upload file <span class="mono" style="font-size: 11px; background: rgba(0,0,0,0.3); padding: 1px 6px; border-radius: 4px; color: #fca5a5;">.sql</span> dari komputer admin</p>
                    </div>
                </div>

                <div class="alert-banner" style="background: rgba(244,63,94,0.06); border: 1px solid rgba(244,63,94,0.15); color: #fda4af; margin-bottom: 16px; font-size: 12.5px;">
                    ⚠️ <span>Restore dari file upload akan <strong>menimpa seluruh data database aktif</strong>. Tindakan ini tidak bisa dibatalkan.</span>
                </div>

                <form method="POST" action="{{ route('admin.secret.backup.restore') }}" enctype="multipart/form-data" id="form-restore-upload">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <label for="inp-backup-file" style="display: block; font-size: 12px; color: #64748b; margin-bottom: 6px; font-weight: 500;">FILE BACKUP (.SQL) — MAKS 50MB</label>
                        <input type="file" name="backup_file" id="inp-backup-file" accept=".sql" class="input-field" required
                               style="padding: 8px 12px; cursor: pointer; color: #94a3b8;">
                    </div>
                    <button type="button" class="btn btn-rose" onclick="confirmRestoreUpload()" id="btn-restore-upload">
                        📤 Upload &amp; Jalankan Restore
                    </button>
                </form>
            </div>

        @endif
    </div>{{-- end max-w --}}

    {{-- ======================== --}}
    {{-- MODAL: HAPUS BACKUP --}}
    {{-- ======================== --}}
    <div class="modal-overlay" id="modal-delete">
        <div class="modal-box">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="font-size: 40px; margin-bottom: 10px;">🗑️</div>
                <h3 style="font-size: 17px; font-weight: 700; color: #f1f5f9; margin: 0 0 6px;">Hapus File Backup?</h3>
                <p style="font-size: 13px; color: #94a3b8; margin: 0;">File yang dihapus tidak bisa dikembalikan.</p>
            </div>
            <div style="background: rgba(244,63,94,0.06); border: 1px solid rgba(244,63,94,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 20px;">
                <p style="font-size: 12px; color: #64748b; margin: 0 0 4px;">FILE YANG AKAN DIHAPUS:</p>
                <p id="modal-delete-filename" class="mono" style="font-size: 13px; color: #fda4af; margin: 0; word-break: break-all;"></p>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeDeleteModal()" class="btn btn-ghost" style="flex: 1; justify-content: center;">
                    Batal
                </button>
                <form id="form-delete" method="POST" style="flex: 1;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-rose" style="width: 100%; justify-content: center;">
                        🗑️ Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ======================== --}}
    {{-- MODAL: RESTORE DARI SERVER --}}
    {{-- ======================== --}}
    <div class="modal-overlay" id="modal-restore">
        <div class="modal-box" style="border-color: rgba(245,158,11,0.3);">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="font-size: 40px; margin-bottom: 10px;">🔄</div>
                <h3 style="font-size: 17px; font-weight: 700; color: #f1f5f9; margin: 0 0 6px;">Konfirmasi Restore?</h3>
                <p style="font-size: 13px; color: #94a3b8; margin: 0;">Database aktif akan ditimpa dengan data dari backup ini.</p>
            </div>
            <div style="background: rgba(245,158,11,0.06); border: 1px solid rgba(245,158,11,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 20px;">
                <p style="font-size: 12px; color: #64748b; margin: 0 0 4px;">FILE BACKUP YANG AKAN DIPAKAI:</p>
                <p id="modal-restore-filename" class="mono" style="font-size: 13px; color: #fcd34d; margin: 0; word-break: break-all;"></p>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeRestoreModal()" class="btn btn-ghost" style="flex: 1; justify-content: center;">
                    Batal
                </button>
                <form id="form-restore-server-modal" method="POST" action="{{ route('admin.secret.backup.restore-server') }}" style="flex: 1;">
                    @csrf
                    <input type="hidden" name="server_backup_file" id="modal-restore-input">
                    <button type="submit" class="btn btn-amber" style="width: 100%; justify-content: center;">
                        🔄 Ya, Restore
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ===== MODAL: DELETE =====
        function openDeleteModal(filename) {
            document.getElementById('modal-delete-filename').textContent = filename;
            const encodedFilename = encodeURIComponent(filename);
            document.getElementById('form-delete').action = '/pahrigantenguye/delete/' + encodedFilename;
            document.getElementById('modal-delete').classList.add('active');
        }
        function closeDeleteModal() {
            document.getElementById('modal-delete').classList.remove('active');
        }

        // ===== MODAL: RESTORE FROM SERVER (via row button) =====
        function openRestoreModal(filename) {
            document.getElementById('modal-restore-filename').textContent = filename;
            document.getElementById('modal-restore-input').value = filename;
            document.getElementById('modal-restore').classList.add('active');
        }
        function closeRestoreModal() {
            document.getElementById('modal-restore').classList.remove('active');
        }

        // ===== CONFIRM: RESTORE FROM SERVER (via select) =====
        function confirmRestoreServer() {
            const select = document.getElementById('inp-server-backup-file');
            if (!select || !select.value) {
                alert('Pilih file backup terlebih dahulu.');
                return;
            }
            const filename = select.options[select.selectedIndex].text;
            if (confirm('Yakin mau restore database dengan file:\n\n"' + select.value + '"?\n\nDatabase aktif akan DITIMPA. Tindakan ini tidak bisa dibatalkan!')) {
                document.getElementById('form-restore-server').submit();
            }
        }

        // ===== CONFIRM: RESTORE UPLOAD FROM PC =====
        function confirmRestoreUpload() {
            const fileInput = document.getElementById('inp-backup-file');
            if (!fileInput || !fileInput.files.length) {
                alert('Pilih file backup (.sql) terlebih dahulu.');
                return;
            }
            const filename = fileInput.files[0].name;
            if (confirm('Yakin mau restore database dengan file:\n\n"' + filename + '"?\n\nDatabase aktif akan DITIMPA. Tindakan ini tidak bisa dibatalkan!')) {
                document.getElementById('form-restore-upload').submit();
            }
        }

        // Tutup modal saat klik overlay
        document.getElementById('modal-delete').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
        document.getElementById('modal-restore').addEventListener('click', function(e) {
            if (e.target === this) closeRestoreModal();
        });

        // Keyboard ESC to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
                closeRestoreModal();
            }
        });
    </script>

<x-inactivity-timer />
</body>
</html>
