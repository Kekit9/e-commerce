<?php

use App\Http\Controllers\CurrencyRateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Currency Rates API Routes
 */
// Get current rates
Route::get('/currency-rates', [CurrencyRateController::class, 'getRates']);


// Manual update trigger
Route::post('/currency-rates/update', [CurrencyRateController::class, 'updateRates']);
