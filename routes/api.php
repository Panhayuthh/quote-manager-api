<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Http\Request;
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

Route::get('/quotes/random', QuoteController::class . '@generate');
Route::get('/quotes', QuoteController::class . '@index');
Route::post('/quotes', QuoteController::class . '@store');
Route::delete('/quotes/{id}', QuoteController::class . '@destroy');