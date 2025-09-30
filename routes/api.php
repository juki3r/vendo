<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Esp8266Controller;

Route::get('/ping', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/onlineCheck', [Esp8266Controller::class, 'onlineCheck']);
Route::post('/responseUpdate', [Esp8266Controller::class, 'responseUpdate']);

Route::get('/esp8266s/{id}/add-rates', [Esp8266Controller::class, 'createRates'])->name('esp8266s.createRates');
Route::post('/esp8266s/{id}/store-rates', [Esp8266Controller::class, 'storeRates'])->name('esp8266s.storeRates');
