<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\VoziloController;
use App\Http\Controllers\DeoController;
use App\Http\Controllers\DijagnostikaController;
use App\Http\Controllers\ServiserController; // DODAJ OVO!
use App\Http\Middleware\CheckServiser;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Serviser rute
Route::middleware('auth')->prefix('serviser')->name('serviser.')->group(function () {
    // Dashboard (ekran 4)
    Route::get('/dashboard', [ServiserController::class, 'dashboard'])->name('dashboard');
    
    // Servisni zahtevi (ekran 8)
    Route::get('/servisni-zahtevi', [ServiserController::class, 'servisniZahtevi'])->name('servisni-zahtevi');
    Route::post('/servis/{servis}/pokreni-dijagnostiku', [ServiserController::class, 'pokreniDijagnostiku'])
        ->name('pokreni-dijagnostiku');
    Route::get('/servis/{servis}/zapis', [ServiserController::class, 'servisniZapis'])
        ->name('servisni-zapis');
    
    // Dijagnostika (ekran 9)
    Route::get('/dijagnostika/{servis}', [ServiserController::class, 'dijagnostika'])->name('dijagnostika.show');
    Route::post('/dijagnostika/{servis}/sacuvaj', [ServiserController::class, 'sacuvajDijagnostiku'])
        ->name('dijagnostika.sacuvaj');
    
    // Popravke (ekran 10)
    Route::get('/popravke', [ServiserController::class, 'popravke'])->name('popravke');
    Route::post('/popravka/{servis}/zavrsi', [ServiserController::class, 'zavrsiPopravku'])
        ->name('popravka.zavrsi');
    
    // Zalihe (ekran 11 - read only)
    Route::get('/zalihe', [ServiserController::class, 'zalihe'])->name('zalihe');
    
    // Ostale serviser rute
    Route::get('/aktivni-servisi', [ServiserController::class, 'aktivniServisi'])->name('aktivni-servisi');
    Route::get('/servis/{servis}', [ServiserController::class, 'detaljiServisa'])->name('detalji-servisa');
});

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