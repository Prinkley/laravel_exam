<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThingController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('things.index') : view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    // Thing routes
    Route::get('/things', [ThingController::class, 'index'])->name('things.index');
    Route::get('/things/my', [ThingController::class, 'myThings'])->name('things.my');
    Route::get('/things/repair', [ThingController::class, 'repairThings'])->name('things.repair');
    Route::get('/things/work', [ThingController::class, 'workThings'])->name('things.work');
    Route::get('/things/used', [ThingController::class, 'usedThings'])->name('things.used');
    Route::get('/things/create', [ThingController::class, 'create'])->name('things.create');
    Route::post('/things', [ThingController::class, 'store'])->name('things.store');
    Route::get('/things/{thing}', [ThingController::class, 'show'])->name('things.show');
    Route::get('/things/{thing}/edit', [ThingController::class, 'edit'])->name('things.edit');
    Route::put('/things/{thing}', [ThingController::class, 'update'])->name('things.update');
    Route::delete('/things/{thing}', [ThingController::class, 'destroy'])->name('things.destroy');
    Route::get('/things/{thing}/assign', [ThingController::class, 'assign'])->name('things.assign');
    Route::post('/things/{thing}/assign', [ThingController::class, 'storeAssignment'])->name('things.storeAssignment');
    // Задание 13: Добавление нового описания
    Route::post('/things/{thing}/descriptions', [ThingController::class, 'storeDescription'])->name('things.storeDescription');
    // Задание 13: Выбор актуального описания
    Route::post('/things/{thing}/descriptions/active', [ThingController::class, 'setActiveDescription'])->name('things.setActiveDescription');

    // Place routes (with admin middleware for create/edit/delete)
    Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
    
    Route::middleware('admin')->group(function () {
        Route::get('/places/create', [PlaceController::class, 'create'])->name('places.create');
        Route::post('/places', [PlaceController::class, 'store'])->name('places.store');
        Route::get('/places/{place}/edit', [PlaceController::class, 'edit'])->name('places.edit');
        Route::put('/places/{place}', [PlaceController::class, 'update'])->name('places.update');
        Route::delete('/places/{place}', [PlaceController::class, 'destroy'])->name('places.destroy');
    });
    
    Route::get('/places/{place}', [PlaceController::class, 'show'])->name('places.show');

    // Usage routes
    Route::get('/usages', [UsageController::class, 'index'])->name('usages.index');
    Route::get('/usages/{usage}', [UsageController::class, 'show'])->name('usages.show');
    Route::get('/usages/{usage}/edit', [UsageController::class, 'edit'])->name('usages.edit');
    Route::put('/usages/{usage}', [UsageController::class, 'update'])->name('usages.update');
    Route::delete('/usages/{usage}', [UsageController::class, 'destroy'])->name('usages.destroy');

    // Задание 15: Маршруты для архива
    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');
    Route::get('/archive/{archived}', [ArchiveController::class, 'show'])->name('archive.show');
    Route::post('/archive/{archived}/restore', [ArchiveController::class, 'restore'])->name('archive.restore');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
