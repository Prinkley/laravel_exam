<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ThingApiController;
use App\Http\Controllers\Api\ArchiveApiController;

// Задание 12: Использование Sanctum token-аутентификации для API
Route::middleware('auth:sanctum')->group(function () {
    // Профиль текущего пользователя
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Задание 12: API маршруты для вещей с token-аутентификацией
    Route::prefix('v1')->name('api.v1.')->group(function () {
        Route::apiResource('things', ThingApiController::class);
        
        // Задание 13: Маршруты для работы с описаниями вещей
        Route::post('/things/{thing}/descriptions', [ThingApiController::class, 'addDescription'])->name('things.addDescription');
        Route::get('/things/{thing}/descriptions', [ThingApiController::class, 'getDescriptions'])->name('things.getDescriptions');
        Route::post('/things/{thing}/descriptions/set-active', [ThingApiController::class, 'setActiveDescription'])->name('things.setActiveDescription');

        // Задание 15: API маршруты для архива
        Route::get('/archive', [ArchiveApiController::class, 'index'])->name('archive.index');
        Route::get('/archive/{archived}', [ArchiveApiController::class, 'show'])->name('archive.show');
        Route::post('/archive/{archived}/restore', [ArchiveApiController::class, 'restore'])->name('archive.restore');
    });
});

// Маршрут для получения токена (без аутентификации)
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Неверные учетные данные'
        ], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Вход выполнен успешно',
        'token' => $token,
        'user' => $user
    ]);
})->name('login');

// Маршрут для выхода
Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    
    return response()->json([
        'success' => true,
        'message' => 'Выход выполнен успешно'
    ]);
})->middleware('auth:sanctum')->name('logout');
