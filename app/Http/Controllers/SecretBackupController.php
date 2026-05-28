<?php

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use App\Support\ManualCaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;
use RuntimeException;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SecretBackupController extends Controller
{
    private const CAPTCHA_CONTEXT = 'secret_backup_panel';
    private const SESSION_OTP_KEY = 'secret_backup_panel.otp';
    private const SESSION_OTP_LAST_SENT_AT_KEY = 'secret_backup_panel.otp_last_sent_at';
    private const SESSION_VERIFIED_UNTIL_KEY = 'secret_backup_panel.verified_until';
    private const VERIFIED_MINUTES = 15;

    public function index(Request $request): View
    {
        $isVerified = $this->isVerified($request);
        $captchaQuestion = '';

        if (! $isVerified) {
            $captchaQuestion = ManualCaptcha::question($request, self::CAPTCHA_CONTEXT);
            if (! $request->session()->has(self::SESSION_OTP_KEY)) {
                $this->refreshOtp($request, true);
            }
        }

        return view('admin.secret-backup-panel', [
            'captchaQuestion' => $captchaQuestion,
            'isVerified' => $isVerified,
            'verifiedUntil' => $request->session()->get(self::SESSION_VERIFIED_UNTIL_KEY),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $this->validateSecurityGate($request);
        $verifiedUntil = now()->addMinutes(self::VERIFIED_MINUTES)->toDateTimeString();
        $request->session()->put(self::SESSION_VERIFIED_UNTIL_KEY, $verifiedUntil);
        $request->session()->forget(self::SESSION_OTP_KEY);
        ManualCaptcha::generate($request, self::CAPTCHA_CONTEXT);

        return redirect()->route('admin.secret.backup.index')
            ->with('success', 'Verifikasi berhasil. Akses backup/restore aktif selama 15 menit.');
    }

    public function backup(Request $request): RedirectResponse
    {
        $this->ensureVerified($request);

        $diskPath = storage_path('app/backups');
        if (! is_dir($diskPath)) {
            mkdir($diskPath, 0755, true);
        }

        $connection = config('database.default');
        $filename = now()->format('Y-m-d_H-i-s')."_{$connection}_backup.sql";
        $fullPath = $diskPath.DIRECTORY_SEPARATOR.$filename;

        if ($connection === 'sqlite') {
            $sqliteDbPath = config('database.connections.sqlite.database');
            if (! $sqliteDbPath || ! File::exists($sqliteDbPath)) {
                return $this->redirectWithError($request, 'Backup gagal: file database sqlite tidak ditemukan.');
            }

            File::copy($sqliteDbPath, $fullPath);
        } elseif (in_array($connection, ['mysql', 'mariadb'], true)) {
            $this->dumpMysqlDatabase($fullPath);
        } else {
            return $this->redirectWithError($request, "Backup belum mendukung driver '{$connection}'.");
        }

        ActivityLogger::log(
            'secret_panel_backup',
            sprintf('Admin %s membuat backup database: %s.', Auth::user()->name, $filename),
            Auth::user(),
            $request
        );

        return back()->with('success', "Backup berhasil dibuat: {$filename}");
    }

    public function restore(Request $request): RedirectResponse
    {
        $this->ensureVerified($request);

        $request->validate([
            'backup_file' => [
                'required',
                'file',
                'max:51200',
                'extensions:sql',
                'mimetypes:text/plain,application/sql,application/x-sql',
            ],
        ]);

        $uploadedFile = $request->file('backup_file');
        $connection = config('database.default');

        if (! $uploadedFile) {
            return $this->redirectWithError($request, 'File backup tidak ditemukan.');
        }

        if (in_array($connection, ['mysql', 'mariadb'], true)) {
            $this->restoreMysqlDatabase($uploadedFile->getRealPath());
        } else {
            return $this->redirectWithError($request, "Restore belum mendukung driver '{$connection}'.");
        }

        ActivityLogger::log(
            'secret_panel_restore',
            sprintf('Admin %s melakukan restore database.', Auth::user()->name),
            Auth::user(),
            $request
        );

        return back()->with('success', 'Restore database berhasil dijalankan.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        if ($this->isVerified($request)) {
            return back()->with('success', 'Sesi sudah terverifikasi, OTP tidak diperlukan.');
        }

        $this->refreshOtp($request, true);

        return back()->with('success', 'OTP baru sudah dikirim ke email admin.');
    }

    private function validateSecurityGate(Request $request): void
    {
        $request->validate([
            'panel_password' => ['required', 'string'],
            'otp' => ['required', 'digits:6'],
            'captcha' => ['required', 'string'],
        ]);

        $otpFromSession = (string) $request->session()->get(self::SESSION_OTP_KEY, '');
        $captchaAnswer = (string) $request->session()->get('manual_captcha.'.self::CAPTCHA_CONTEXT.'.answer', '');

        if ($request->input('panel_password') !== (string) env('SECRET_PANEL_PASSWORD', '')) {
            throw ValidationException::withMessages([
                'panel_password' => 'Password panel salah.',
            ]);
        }

        if ($request->input('otp') !== $otpFromSession) {
            throw ValidationException::withMessages([
                'otp' => 'OTP salah.',
            ]);
        }

        if ((string) $request->input('captcha') !== $captchaAnswer) {
            throw ValidationException::withMessages([
                'captcha' => 'Captcha manual salah.',
            ]);
        }
    }

    private function ensureVerified(Request $request): void
    {
        if (! $this->isVerified($request)) {
            throw ValidationException::withMessages([
                'panel' => 'Sesi belum terverifikasi. Lakukan verifikasi dulu.',
            ]);
        }
    }

    private function isVerified(Request $request): bool
    {
        $verifiedUntil = $request->session()->get(self::SESSION_VERIFIED_UNTIL_KEY);
        if (! $verifiedUntil) {
            return false;
        }

        return now()->lte($verifiedUntil);
    }

    private function refreshOtp(Request $request, bool $sendEmail = false): string
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $request->session()->put(self::SESSION_OTP_KEY, $otp);

        if ($sendEmail) {
            $lastSentAt = $request->session()->get(self::SESSION_OTP_LAST_SENT_AT_KEY);
            $lastSentAtTime = $lastSentAt ? Carbon::parse((string) $lastSentAt) : null;
            $canSendEmail = ! $lastSentAtTime || $lastSentAtTime->diffInSeconds(now()) >= 30;

            if ($canSendEmail) {
                $this->sendOtpToAdminEmail($otp);
                $request->session()->put(self::SESSION_OTP_LAST_SENT_AT_KEY, now()->toDateTimeString());
            }
        }

        return $otp;
    }

    private function sendOtpToAdminEmail(string $otp): void
    {
        $admin = Auth::user();
        if (! $admin || ! $admin->email) {
            throw ValidationException::withMessages([
                'panel' => 'Email admin tidak ditemukan, OTP tidak bisa dikirim.',
            ]);
        }

        Mail::raw(
            "OTP untuk Secret Backup Panel kamu adalah: {$otp}. Jangan bagikan kode ini ke siapa pun.",
            function ($message) use ($admin): void {
                $message->to($admin->email)
                    ->subject('OTP Secret Backup Panel');
            }
        );
    }

    private function dumpMysqlDatabase(string $targetPath): void
    {
        $mysqldumpBinary = $this->resolveMysqlBinary('mysqldump');
        $connectionName = config('database.default');
        $config = config("database.connections.{$connectionName}");

        $host = $this->normalizeHost((string) ($config['host'] ?? '127.0.0.1'));
        $port = (string) ($config['port'] ?? '3306');
        $database = $config['database'] ?? '';
        $username = $config['username'] ?? '';
        $password = $config['password'] ?? '';

        $connectionAttempts = $this->buildMysqlConnectionAttempts($host, $port);
        $lastError = 'Koneksi database gagal.';

        foreach ($connectionAttempts as $attempt) {
            $command = array_merge(
                [
                    $mysqldumpBinary,
                    '--host='.$attempt['host'],
                    '--protocol='.$attempt['protocol'],
                    '--user='.$username,
                    '--single-transaction',
                    '--quick',
                    '--skip-lock-tables',
                    '--no-tablespaces',
                ],
                $attempt['port'] !== null ? ['--port='.$attempt['port']] : [],
                $password !== '' ? ['--password='.$password] : [],
                [$database]
            );

            $result = Process::path(base_path())
                ->env($this->windowsProcessEnvironment())
                ->run($command);
            if ($result->successful()) {
                File::put($targetPath, $result->output());

                return;
            }

            $lastError = trim($result->errorOutput()) !== ''
                ? trim($result->errorOutput())
                : trim($result->output());
        }

        throw ValidationException::withMessages([
            'panel' => 'Backup gagal: mysqldump error. '.$lastError,
        ]);
    }

    private function restoreMysqlDatabase(string $sqlFilePath): void
    {
        $mysqlBinary = $this->resolveMysqlBinary('mysql');
        $connectionName = config('database.default');
        $config = config("database.connections.{$connectionName}");

        $host = $this->normalizeHost((string) ($config['host'] ?? '127.0.0.1'));
        $port = (string) ($config['port'] ?? '3306');
        $database = $config['database'] ?? '';
        $username = $config['username'] ?? '';
        $password = $config['password'] ?? '';
        $connectionAttempts = $this->buildMysqlConnectionAttempts($host, $port);
        $lastError = 'Koneksi database gagal.';
        $sourceCommand = 'source '.str_replace('\\', '/', $sqlFilePath);

        foreach ($connectionAttempts as $attempt) {
            $command = array_merge(
                [
                    $mysqlBinary,
                    '--host='.$attempt['host'],
                    '--protocol='.$attempt['protocol'],
                    '--user='.$username,
                    '--execute='.$sourceCommand,
                ],
                $attempt['port'] !== null ? ['--port='.$attempt['port']] : [],
                $password !== '' ? ['--password='.$password] : [],
                [$database]
            );

            $result = Process::path(base_path())
                ->env($this->windowsProcessEnvironment())
                ->timeout(0)
                ->run($command);
            if ($result->successful()) {
                return;
            }

            $lastError = trim($result->errorOutput()) !== ''
                ? trim($result->errorOutput())
                : trim($result->output());
        }

        throw ValidationException::withMessages([
            'panel' => 'Restore gagal: mysql client error. '.$lastError,
        ]);
    }

    private function resolveMysqlBinary(string $binaryName): string
    {
        $extension = DIRECTORY_SEPARATOR === '\\' ? '.exe' : '';
        $binaryFile = $binaryName.$extension;

        $envPath = env('MYSQL_BIN_PATH');
        $candidates = [];

        if ($envPath) {
            $candidates[] = rtrim($envPath, '\\/').DIRECTORY_SEPARATOR.$binaryFile;
        }

        $candidates = array_merge($candidates, [
            'D:\\Apk\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\'.$binaryFile,
            'D:\\Apk\\laragon\\bin\\mysql\\mysql-8.0.30\\bin\\'.$binaryFile,
            'D:\\Apk\\laragon\\bin\\mysql\\mariadb-11.4.2-winx64\\bin\\'.$binaryFile,
            'D:\\Apk\\laragon\\bin\\mysql\\mariadb-10.11.2-winx64\\bin\\'.$binaryFile,
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\'.$binaryFile,
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30\\bin\\'.$binaryFile,
            'C:\\laragon\\bin\\mysql\\mariadb-11.4.2-winx64\\bin\\'.$binaryFile,
            'C:\\laragon\\bin\\mysql\\mariadb-10.11.2-winx64\\bin\\'.$binaryFile,
            'C:\\xampp\\mysql\\bin\\'.$binaryFile,
            'C:\\wamp64\\bin\\mysql\\mysql8.0.31\\bin\\'.$binaryFile,
        ]);

        $laragonMysqlGlobD = glob('D:\\Apk\\laragon\\bin\\mysql\\*\\bin\\'.$binaryFile);
        if (is_array($laragonMysqlGlobD)) {
            $candidates = array_merge($candidates, $laragonMysqlGlobD);
        }

        $laragonMysqlGlob = glob('C:\\laragon\\bin\\mysql\\*\\bin\\'.$binaryFile);
        if (is_array($laragonMysqlGlob)) {
            $candidates = array_merge($candidates, $laragonMysqlGlob);
        }

        foreach ($candidates as $candidate) {
            if (is_string($candidate) && $candidate !== '' && file_exists($candidate)) {
                return $candidate;
            }
        }

        return $binaryName;
    }

    private function normalizeHost(string $host): string
    {
        $trimmed = trim($host);
        if ($trimmed === '' || strtolower($trimmed) === 'localhost') {
            return '127.0.0.1';
        }

        return $trimmed;
    }

    /**
     * @return list<array{host: string, port: string|null, protocol: string}>
     */
    private function buildMysqlConnectionAttempts(string $configuredHost, string $port): array
    {
        $attempts = [[
            'host' => $configuredHost,
            'port' => $port,
            'protocol' => 'TCP',
        ]];
        $isWindows = DIRECTORY_SEPARATOR === '\\';

        if (! $isWindows && in_array($configuredHost, ['127.0.0.1', 'localhost'], true)) {
            $attempts[] = [
                'host' => 'localhost',
                'port' => $port,
                'protocol' => 'TCP',
            ];
        }

        return array_values(array_unique($attempts, SORT_REGULAR));
    }

    private function redirectWithError(Request $request, string $message): RedirectResponse
    {
        if (! $this->isVerified($request)) {
            $this->refreshOtp($request, true);
            ManualCaptcha::generate($request, self::CAPTCHA_CONTEXT);
        }

        return back()->withErrors(['panel' => $message]);
    }

    /**
     * @return array<string, string>
     */
    private function windowsProcessEnvironment(): array
    {
        if (DIRECTORY_SEPARATOR !== '\\') {
            return [];
        }

        $systemRoot = (string) (getenv('SYSTEMROOT') ?: getenv('SystemRoot') ?: 'C:\\Windows');
        $winDir = (string) (getenv('WINDIR') ?: $systemRoot);
        $comSpec = (string) (getenv('ComSpec') ?: $winDir.'\\System32\\cmd.exe');

        return [
            'SYSTEMROOT' => $systemRoot,
            'SystemRoot' => $systemRoot,
            'WINDIR' => $winDir,
            'ComSpec' => $comSpec,
        ];
    }
}
