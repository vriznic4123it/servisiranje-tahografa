<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\VoziloController;
use App\Http\Controllers\DeoController;
use App\Http\Controllers\DijagnostikaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/home', [ServisController::class, 'index'])->name('home');
    Route::get('/servisi/zakazi', [ServisController::class, 'createZakazivanje'])->name('servis.createZakazi');
    Route::post('/zakazi-servis', [ServisController::class, 'zakazi'])->name('servis.zakazi');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('servisi', ServisController::class);
    Route::resource('vozila', VoziloController::class);
    Route::resource('delovi', DeoController::class);
    Route::resource('diagnostike', DijagnostikaController::class);

});

require __DIR__.'/auth.php';
