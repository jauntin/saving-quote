<?php

use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Jauntin\SavingQuote\Http\Controllers\QuoteProgressController;
use Jauntin\SavingQuote\Http\RouteNames;
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

Route::prefix('api/v1')->group(function () {
    Route::get('/quote/progress/{hash}', [QuoteProgressController::class, 'single'])
        ->name(RouteNames::GET_QUOTE_PROGRESS)
        ->withoutMiddleware(ConvertEmptyStringsToNull::class);
    Route::post('/quote/progress', [QuoteProgressController::class, 'create'])
        ->name(RouteNames::CREATE_QUOTE_PROGRESS)
        ->withoutMiddleware(ConvertEmptyStringsToNull::class);
});
