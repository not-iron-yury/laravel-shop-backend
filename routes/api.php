<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

// Доступно без авторизации
// register и login выполняются до авторизации (пользователь ещё не имеет токена).
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Только для авторизованных пользователей
// Middleware auth:sanctum требует уже авторизованного пользователя (с действующим токеном).
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('products', ProductController::class);
});

Route::middleware(['auth:sanctum'])->get('/admin/test', function() {
    // проверка на несоответствие правилу 'isAdmin'
    if(Gate::denies('isAdmin')){
        abort(403, 'Доступ запрещен.');
    }

    return 'Добро пожаловать в админку';
});
