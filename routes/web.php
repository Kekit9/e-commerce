<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ServiceController;
use App\Models\Maker;
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
Route::post('/product', [ProductController::class, 'create']);
Route::get('/products-list', [ProductController::class, 'index']);
Route::put('/product/{id}', [ProductController::class, 'update']);
Route::delete('/product/{id}', [ProductController::class, 'delete']);

Route::inertia('/services', 'ServicePage');
Route::post('/service', [ServiceController::class, 'create']);
Route::get('/services-list', [ServiceController::class, 'index']);
Route::put('/service/{id}', [ServiceController::class, 'update']);
Route::delete('/service/{id}', [ServiceController::class, 'delete']);

