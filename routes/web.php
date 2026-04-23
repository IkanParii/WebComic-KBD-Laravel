<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Models\Cerita;
use App\Models\Genre; 
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landing');
});

// --- RUTE PROFILE & USER ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rute Baca Cerita
    Route::get('/cerita/{id}', [UserController::class, 'baca'])->name('cerita.baca');

    // Rute buat Dashboard User & Fitur Favorit
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    
    // 🔥 THROTTLE FAVORITE: Cegah user pake auto-clicker nambahin favorit (Maks 30 kali per menit)
    Route::post('/user/favorite/{id}', [UserController::class, 'toggleFavorite'])
        ->middleware('throttle:30,1')
        ->name('user.favorite.toggle');
});

// --- RUTE PUBLISHER ---
Route::middleware(['auth', 'verified', 'publisher'])->prefix('publisher')->name('publisher.')->group(function () {
    Route::get('/daftar-cerita', [PublisherController::class, 'index'])->name('index');
    Route::get('/tambah-cerita', [PublisherController::class, 'create'])->name('create');
    
    // 🔥 THROTTLE POST CERITA: Cegah bot spam upload cerita sampah (Maks 5 cerita per menit)
    Route::post('/tambah-cerita', [PublisherController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('store');
        
    Route::get('/edit-cerita/{id}', [PublisherController::class, 'edit'])->name('edit');
    Route::put('/update-cerita/{id}', [PublisherController::class, 'update'])->name('update');
    Route::delete('/hapus-cerita/{id}', [PublisherController::class, 'destroy'])->name('destroy');
});

// --- RUTE HOME FINAL ---
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

// --- RUTE ADMIN ---
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::delete('/user/{id}', [AdminController::class, 'destroyUser'])->name('user.destroy');
    Route::delete('/cerita/{id}', [AdminController::class, 'destroyCerita'])->name('cerita.destroy');
    Route::put('/user/{id}', [AdminController::class, 'updateUser'])->name('user.update');
});

require __DIR__.'/auth.php';