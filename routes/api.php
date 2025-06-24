<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Gate;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// /user больше не нужен — теперь его роль выполняет /me, но с контроллером.

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->get('/admin/test', function() {

    // проверка на несоответствие правилу 'isAdmin'
    if(Gate::denies('isAdmin')){
        abort(403, 'Доступ запрещен.');
    }

    return 'Добро пожаловать в админку';
});
