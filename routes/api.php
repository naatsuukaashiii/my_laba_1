<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Маршруты аутентификации (не требуют токена)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Маршруты, требующие аутентификации (требуется токен)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/tokens', [UserController::class, 'tokens']);
    Route::post('/revoke-all-tokens', [UserController::class, 'revokeAllTokens']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users', [UserController::class, 'index']); // Добавьте здесь
    Route::get('/users/{id}', [UserController::class, 'show']); // Добавьте здесь
    Route::post('/update-password', [UserController::class, 'updatePassword']); // Добавьте маршрут для изменения пароля
});
