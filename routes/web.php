<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController; // <-- Gw tambahin ini brow
use App\Models\Cerita;
use App\Models\Genre; 
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landing');
});

// --- RUTE PROFILE & USER ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    //Rute Baca Cerita
    Route::get('/cerita/{id}', [App\Http\Controllers\UserController::class, 'baca'])->name('cerita.baca');

    // Rute buat Dashboard User & Fitur Favorit (BARU DITAMBAH)
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/user/favorite/{id}', [UserController::class, 'toggleFavorite'])->name('user.favorite.toggle');
});

// --- RUTE PUBLISHER ---
Route::middleware(['auth', 'publisher'])->prefix('publisher')->name('publisher.')->group(function () {
    Route::get('/daftar-cerita', [PublisherController::class, 'index'])->name('index');
    Route::get('/tambah-cerita', [PublisherController::class, 'create'])->name('create');
    Route::post('/tambah-cerita', [PublisherController::class, 'store'])->name('store');
    Route::get('/edit-cerita/{id}', [PublisherController::class, 'edit'])->name('edit');
    Route::put('/update-cerita/{id}', [PublisherController::class, 'update'])->name('update');
    Route::delete('/hapus-cerita/{id}', [PublisherController::class, 'destroy'])->name('destroy');
});

// --- RUTE HOME FINAL ---
Route::get('/home', function (Request $request) {
    $query = Cerita::with('genres')->latest();

    // Filter Search Judul
    if ($request->filled('search')) {
        $query->where('judul', 'like', '%' . $request->search . '%');
    }

    // Filter Genre
    if ($request->filled('genre')) {
        $query->whereHas('genres', function ($q) use ($request) {
            $q->where('nama_genre', $request->genre);
        });
    }

    $ceritas = $query->get(); 
    $genres = Genre::all(); 

    return view('home', compact('ceritas', 'genres'));
})->middleware(['auth'])->name('home');

// --- RUTE ADMIN ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::delete('/user/{id}', [AdminController::class, 'destroyUser'])->name('user.destroy');
    Route::delete('/cerita/{id}', [AdminController::class, 'destroyCerita'])->name('cerita.destroy');
});

require __DIR__.'/auth.php';