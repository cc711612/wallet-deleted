<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\LogoutController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\Apis\Wallets\WalletController;

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
Route::group(['middleware' => [], 'as' => 'api.',], function () {
    # 需要member_token的
    Route::group(['middleware' => ['VerifyApi']], function () {
        # 登出相關
        Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
            Route::name("logout")->post("/logout", [LogoutController::class, 'logout']);
        });
        Route::resource('wallet', WalletController::class)->only(['store', 'update', 'destroy']);
        Route::group(['as' => 'wallet.','prefix' => 'wallet'], function () {
            Route::name("index")->post("/list", [WalletController::class, 'index']);
        });
    });
    # 登入相關
    Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
        Route::name("login")->post("/login", [LoginController::class, 'login']);
    });
});
Route::fallback(function () {
    return response([
        'code'    => 400,
        'status'  => false,
        'message' => '不支援此方法',
    ]);
});
