<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyMerchItemController;
use App\Http\Controllers\SendCoinController;
use App\Http\Controllers\UserInfoController;
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

Route::post('/auth', AuthController::class);
Route::get('/info', UserInfoController::class)->middleware(['auth']);
Route::post('/sendCoin', SendCoinController::class)->middleware(['auth']);
Route::get('/buy/{item}', BuyMerchItemController::class)->middleware(['auth']);
