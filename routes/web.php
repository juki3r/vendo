<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    //Show all esp8266
    Route::get('/vendo', [DashboardController::class, 'index'])->name('esp8266s.index');

    // CRUD WIFI TIMER RATES
    Route::post('/vendo/{esp}/rates', [DashboardController::class, 'storeRate'])->name('esp8266s.storeRate');
    Route::put('/vendo/{esp}/rates/{coin}', [DashboardController::class, 'updateRate'])->name('esp8266s.updateRate');
    Route::delete('/vendo/{esp}/rates/{coin}', [DashboardController::class, 'deleteRate'])->name('esp8266s.deleteRate');

    //View all sales
    Route::get('/view/sales', [DashboardController::class, 'viewSalesHistory'])->name('viewSalesHistory');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
