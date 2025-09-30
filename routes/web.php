<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Esp8266Controller;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/esp8266s/{id}/add-rates', [Esp8266Controller::class, 'createRates'])->name('esp8266s.createRates');
Route::post('/esp8266s/{id}/store-rates', [Esp8266Controller::class, 'storeRates'])->name('esp8266s.storeRates');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
