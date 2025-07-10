<?php

use App\Http\Controllers\API\TranslationController;
use App\Http\Middleware\TokenAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware([TokenAuthMiddleware::class])->group(function () {
    Route::get('/translations', [TranslationController::class, 'index']);
    Route::post('/translations', [TranslationController::class, 'store']);
    Route::get('/translations/{key}/{locale}', [TranslationController::class, 'show']);
    Route::put('/translations/{key}/{locale}', [TranslationController::class, 'update']);
    Route::delete('/translations/{key}/{locale}', [TranslationController::class, 'destroy']);
    Route::get('/translations/export/{locale}', [TranslationController::class, 'export']);
});
