<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;


Route::get('/ping', function () {
    return response()->json(['message' => 'API is working again!']);
});
