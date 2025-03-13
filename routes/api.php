<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json([
        'code' => 200,
        'message' => 'Welcome to the Quote API',
    ]);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Route::get('/quotes/random', [QuoteController::class, 'generate'])->middleware('auth:sanctum');

// Route::get('/quotes', [QuoteController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/quotes', [QuoteController::class, 'index']);
    Route::post('/quotes', [QuoteController::class, 'store']);
    Route::delete('/quotes/{id}', [QuoteController::class, 'destroy']);
});