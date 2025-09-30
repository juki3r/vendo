<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Esp8266Controller;

Route::get('/ping', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/onlineCheck', [Esp8266Controller::class, 'onlineCheck']);
