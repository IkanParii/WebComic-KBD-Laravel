{{--
    Komponen Inactivity Timer
    ===========================
    Menampilkan countdown toast warning sebelum auto-logout,
    dan melakukan logout otomatis jika tidak ada aktivitas.

    Cara pakai: taruh komponen ini di dalam <body> halaman yang butuh auto-logout.

    @param int $timeoutMinutes - durasi inactivity timeout dalam menit (dari server)
    @param string $logoutRoute  - URL untuk logout POST action
    @param int $warnBeforeSeconds - berapa detik sebelum logout tampilkan warning (default: 60)
--}}
@php
    $timeoutMinutes   = (int) env('SESSION_INACTIVITY_TIMEOUT', 30);
    $timeoutMs        = $timeoutMinutes * 60 * 1000;
    $warnBeforeMs     = min(60_000, (int) ($timeoutMs * 0.2)); // 20% dari timeout atau maks 60 detik
    $logoutUrl        = route('logout');
    $csrfToken        = csrf_token();
@endphp

{{-- Hidden logout form --}}
<form id="__inactivity_logout_form" method="POST" action="{{ $logoutUrl }}" style="display:none;">
    @csrf
</form>

{{-- Toast Warning --}}
<div id="__inactivity_toast"
     role="alert"
     aria-live="assertive"
     style="
        display: none;
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99999;
        max-width: 340px;
        width: calc(100% - 48px);
        background: linear-gradient(135deg, #1e1b4b, #1e293b);
        border: 1px solid rgba(245, 158, 11, 0.4);
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5), 0 0 0 1px rgba(245,158,11,0.1);
        font-family: 'Inter', 'Poppins', sans-serif;
        animation: __toast_slide_in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
     ">
    <style>
        @keyframes __toast_slide_in {
            from { opacity: 0; transform: translateY(16px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0)    scale(1); }
        }
        @keyframes __toast_slide_out {
            from { opacity: 1; transform: translateY(0)    scale(1); }
            to   { opacity: 0; transform: translateY(16px) scale(0.95); }
        }
        #__inactivity_toast.hiding {
            animation: __toast_slide_out 0.25s ease forwards;
        }
        #__inactivity_progress_bar {
            height: 3px;
            border-radius: 99px;
            background: linear-gradient(90deg, #f59e0b, #ef4444);
            transition: width 1s linear;
        }
        #__btn_stay_logged_in {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #1c1917;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        #__btn_stay_logged_in:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(245,158,11,0.4); }
        #__btn_logout_now {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 12px;
            background: transparent;
            color: #94a3b8;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        #__btn_logout_now:hover { color: #f1f5f9; background: rgba(255,255,255,0.07); }
    </style>

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
        <span style="font-size:20px; flex-shrink:0;">⏳</span>
        <div>
            <p style="margin:0; font-size:13px; font-weight:700; color:#fde68a; line-height:1.3;">Sesi hampir berakhir!</p>
            <p style="margin:3px 0 0; font-size:12px; color:#94a3b8;">Tidak ada aktivitas terdeteksi.</p>
        </div>
    </div>

    {{-- Progress bar --}}
    <div style="background:rgba(255,255,255,0.06); border-radius:99px; overflow:hidden; margin-bottom:12px;">
        <div id="__inactivity_progress_bar" style="width:100%;"></div>
    </div>

    {{-- Countdown --}}
    <p style="margin:0 0 12px; font-size:12.5px; color:#cbd5e1;">
        Otomatis logout dalam <strong id="__inactivity_countdown" style="color:#fbbf24; font-size:14px;">60</strong> detik.
    </p>

    {{-- Action buttons --}}
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <button id="__btn_stay_logged_in" onclick="__inactivity_keepAlive()">
            ✅ Tetap Login
        </button>
        <button id="__btn_logout_now" onclick="__inactivity_logout()">
            🚪 Logout Sekarang
        </button>
    </div>
</div>

{{-- Floating Debug Timer --}}
<div id="__inactivity_floating_timer" style="
    position: fixed;
    bottom: 24px;
    left: 24px;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #94a3b8;
    padding: 8px 16px;
    border-radius: 99px;
    font-size: 13px;
    font-weight: 600;
    font-family: 'Inter', 'Poppins', monospace;
    z-index: 99998;
    pointer-events: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
">
    <span style="display:inline-block; width:8px; height:8px; background:#10b981; border-radius:50%; box-shadow:0 0 8px #10b981;"></span>
    Logout dlm: <span id="__inactivity_time_left" style="color: #f8fafc; font-weight: 700; width: 40px; text-align: right;">--:--</span>
</div>

<script>
(function () {
    'use strict';

    // ── Konfigurasi ────────────────────────────────────────────────────
    const TIMEOUT_MS      = {{ $timeoutMs }};       // Total inactivity timeout
    const WARN_BEFORE_MS  = {{ $warnBeforeMs }};    // Mulai warning N ms sebelum logout
    const LOGOUT_URL      = '{{ $logoutUrl }}';
    const CSRF_TOKEN      = '{{ $csrfToken }}';

    // ── State ──────────────────────────────────────────────────────────
    let inactivityTimer   = null;
    let warningTimer      = null;
    let countdownInterval = null;
    let warningShown      = false;
    let countdownSeconds  = Math.round(WARN_BEFORE_MS / 1000);

    // Timer state for floating debug timer
    let totalTimeRemaining = TIMEOUT_MS / 1000;
    let floatingTimerInterval = null;

    // ── DOM refs ───────────────────────────────────────────────────────
    const toast          = document.getElementById('__inactivity_toast');
    const countdownEl    = document.getElementById('__inactivity_countdown');
    const progressBar    = document.getElementById('__inactivity_progress_bar');
    const floatingTimeEl = document.getElementById('__inactivity_time_left');

    // ── Floating Timer Display ─────────────────────────────────────────
    function startFloatingTimer() {
        totalTimeRemaining = TIMEOUT_MS / 1000;
        clearInterval(floatingTimerInterval);
        
        function updateDisplay() {
            if (totalTimeRemaining < 0) totalTimeRemaining = 0;
            const m = Math.floor(totalTimeRemaining / 60).toString().padStart(2, '0');
            const s = (Math.floor(totalTimeRemaining) % 60).toString().padStart(2, '0');
            if (floatingTimeEl) {
                floatingTimeEl.textContent = m + ':' + s;
            }
        }
        
        updateDisplay();
        
        floatingTimerInterval = setInterval(function() {
            totalTimeRemaining -= 1;
            updateDisplay();
            if (totalTimeRemaining <= 0) {
                clearInterval(floatingTimerInterval);
            }
        }, 1000);
    }

    // ── Reset semua timer ──────────────────────────────────────────────
    function resetTimers() {
        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);
        clearInterval(countdownInterval);

        startFloatingTimer();

        // Set warning timer
        warningTimer = setTimeout(showWarning, TIMEOUT_MS - WARN_BEFORE_MS);

        // Set logout timer (fallback, middleware server yang jadi sumber kebenaran)
        inactivityTimer = setTimeout(__inactivity_logout, TIMEOUT_MS);

        // Kalau warning sedang tampil, sembunyikan
        if (warningShown) {
            hideWarning();
        }
    }

    // ── Tampilkan warning toast ────────────────────────────────────────
    function showWarning() {
        if (warningShown) return;
        warningShown = true;

        countdownSeconds = Math.round(WARN_BEFORE_MS / 1000);
        countdownEl.textContent = countdownSeconds;
        progressBar.style.width = '100%';

        toast.style.display = 'block';
        toast.classList.remove('hiding');

        // Countdown per detik
        countdownInterval = setInterval(function () {
            countdownSeconds -= 1;
            if (countdownSeconds <= 0) {
                countdownSeconds = 0;
                countdownEl.textContent = '0';
                clearInterval(countdownInterval);
                return;
            }
            countdownEl.textContent = countdownSeconds;
            // Update progress bar
            const pct = (countdownSeconds / Math.round(WARN_BEFORE_MS / 1000)) * 100;
            progressBar.style.width = pct + '%';
        }, 1000);
    }

    // ── Sembunyikan warning toast ──────────────────────────────────────
    function hideWarning() {
        warningShown = false;
        clearInterval(countdownInterval);
        toast.classList.add('hiding');
        setTimeout(function () {
            toast.style.display = 'none';
            toast.classList.remove('hiding');
        }, 280);
    }

    // ── Keep alive (user klik "Tetap Login") ──────────────────────────
    window.__inactivity_keepAlive = function () {
        hideWarning();
        resetTimers();

        // Ping server supaya server-side last_activity juga diperbarui
        fetch(window.location.href, {
            method: 'HEAD',
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
        }).catch(function () { /* silent fail */ });
    };

    // ── Logout ─────────────────────────────────────────────────────────
    window.__inactivity_logout = function () {
        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);
        clearInterval(countdownInterval);

        const form = document.getElementById('__inactivity_logout_form');
        if (form) {
            form.submit();
        } else {
            window.location.href = LOGOUT_URL;
        }
    };

    // ── Event listeners (reset timer saat ada aktivitas) ───────────────
    const ACTIVITY_EVENTS = ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'];

    // Throttle agar tidak terlalu sering reset (maks 1x per 10 detik)
    let lastResetAt = 0;
    let lastPingAt = Date.now();

    function onActivity() {
        const now = Date.now();
        if (now - lastResetAt < 10_000) return;
        lastResetAt = now;
        resetTimers();

        // Ping server (refresh session backend) maks 1x setiap 5 menit
        // biar kalau user asyik scroll baca komik lama, server nggak nge-logout mereka
        if (now - lastPingAt > 300_000) {
            lastPingAt = now;
            fetch(window.location.href, {
                method: 'HEAD',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
            }).catch(function () { /* silent fail */ });
        }
    }

    ACTIVITY_EVENTS.forEach(function (event) {
        document.addEventListener(event, onActivity, { passive: true });
    });

    // ── Init ───────────────────────────────────────────────────────────
    resetTimers();

    // Handle visibility change — reset timer saat tab kembali aktif
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') {
            onActivity();
        }
    });
})();
</script>
