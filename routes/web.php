<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);

Route::post('/users/{user}', [UserController::class, 'update']);

Route::prefix('games')->controller(GameController::class)->group(function () {
    Route::post('/', 'create');
    Route::get('/{game}', 'show');
    Route::put('/{game}', 'update');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
