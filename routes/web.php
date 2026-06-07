<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SecretBackupController;
use App\Http\Controllers\UserController;
use App\Models\Cerita;
use App\Models\Genre;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landing');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cerita/{id}', [UserController::class, 'baca'])->name('cerita.baca');
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

    Route::post('/user/favorite/{id}', [UserController::class, 'toggleFavorite'])
        ->middleware('throttle:30,1')
        ->name('user.favorite.toggle');
});

Route::middleware(['auth', 'publisher.verify', 'publisher'])->prefix('publisher')->name('publisher.')->group(function () {
    Route::get('/daftar-cerita', [PublisherController::class, 'index'])->name('index');
    Route::get('/tambah-cerita', [PublisherController::class, 'create'])->name('create');
    Route::post('/tambah-cerita', [PublisherController::class, 'store'])->middleware('throttle:5,1')->name('store');
    Route::get('/edit-cerita/{id}', [PublisherController::class, 'edit'])->name('edit');
    Route::put('/update-cerita/{id}', [PublisherController::class, 'update'])->name('update');
    Route::delete('/hapus-cerita/{id}', [PublisherController::class, 'destroy'])->name('destroy');
});

Route::get('/home', function (Request $request) {
    $query = Cerita::with('genres')->latest();

    if ($request->filled('search')) {
        $query->where('judul', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('genre')) {
        $query->whereHas('genres', function ($q) use ($request) {
            $q->where('nama_genre', $request->genre);
        });
    }

    $ceritas = $query->get();
    $genres = Genre::all();

    return view('home', compact('ceritas', 'genres'));
})->middleware(['auth', 'verified'])->name('home');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::delete('/user/{id}', [AdminController::class, 'destroyUser'])->name('user.destroy');
    Route::delete('/cerita/{id}', [AdminController::class, 'destroyCerita'])->name('cerita.destroy');
    Route::put('/user/{id}', [AdminController::class, 'updateUser'])->name('user.update');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('pahrigantenguye')->name('admin.secret.backup.')->group(function () {
    Route::get('/', [SecretBackupController::class, 'index'])->name('index');
    Route::post('/verify', [SecretBackupController::class, 'verify'])->middleware('throttle:5,1')->name('verify');
    Route::post('/resend-otp', [SecretBackupController::class, 'resendOtp'])->middleware('throttle:3,1')->name('resend-otp');
    Route::post('/backup', [SecretBackupController::class, 'backup'])->middleware('throttle:3,1')->name('store');
    Route::post('/restore', [SecretBackupController::class, 'restore'])->middleware('throttle:3,1')->name('restore');
    Route::get('/download/{filename}', [SecretBackupController::class, 'downloadBackup'])->middleware('throttle:10,1')->name('download');
    Route::delete('/delete/{filename}', [SecretBackupController::class, 'deleteBackup'])->middleware('throttle:10,1')->name('delete');
    Route::post('/restore-server', [SecretBackupController::class, 'restoreFromServer'])->middleware('throttle:3,1')->name('restore-server');
});

require __DIR__.'/auth.php';
