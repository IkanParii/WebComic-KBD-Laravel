<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublisherController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'publisher'])->prefix('publisher')->name('publisher.')->group(function () {
    Route::get('/daftar-cerita', [PublisherController::class, 'index'])->name('index');
    Route::get('/tambah-cerita', [PublisherController::class, 'create'])->name('create');
    Route::post('/tambah-cerita', [PublisherController::class, 'store'])->name('store');
    Route::get('/edit-cerita/{id}', [PublisherController::class, 'edit'])->name('edit');
    Route::put('/update-cerita/{id}', [PublisherController::class, 'update'])->name('update');
});

require __DIR__.'/auth.php';
