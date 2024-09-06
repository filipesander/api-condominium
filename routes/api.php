<?php

use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PorteiroController;
use App\Http\Controllers\PositionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('/positions', PositionController::class);
Route::apiResource('owners', OwnerController::class);
