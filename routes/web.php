<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::inertia('/login-signup', 'LoginSignupPage');

Route::post('/registration', [RegistrationController::class, 'registration']);
Route::post('/authorization', [AuthorizationController::class, 'authorization']);

Route::inertia('/main', 'MainPage')->name('main');

Route::inertia('/products', 'ProductPage');
Route::inertia('/services', 'ServicePage');

Route::prefix('api')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('products', ProductController::class);

    Route::apiResource('services', ServiceController::class);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/export-catalog', [ExportController::class, 'exportCatalog']);
});
