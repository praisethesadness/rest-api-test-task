<?php

use App\Http\Controllers\FavouriteQuoteController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('users', UserController::class)->except('index');
Route::apiResource('favourites', FavouriteQuoteController::class)->only(['store', 'destroy']);

Route::get('quotes', [QuoteController::class, 'index']);
Route::get('quotes/non-favourites', [FavouriteQuoteController::class, 'nonFavourites']);