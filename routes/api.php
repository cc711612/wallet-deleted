<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\LogoutController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\Apis\Wallets\WalletController;
use App\Http\Controllers\Apis\Wallets\WalletDetailController;

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
        # 帳本
        Route::resource('wallet', WalletController::class)->only(['store', 'update', 'destroy']);
    });
    # 需要wallet_member_token的
    Route::group(['middleware' => ['VerifyWalletMemberApi']], function () {
        Route::group(['as' => 'wallet.', 'prefix' => 'wallet'], function () {
            Route::name("index")->post("/list", [WalletController::class, 'index']);
            # 帳本明細
            Route::group(['prefix' => '{wallet}'], function () {
                Route::resource('detail', WalletDetailController::class)->only(['store', 'update', 'destroy']);
                Route::name("detail.index")->post("/detail/list", [WalletDetailController::class, 'index']);
            });
        });
    });
    # 登入相關
    Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
        Route::name("login")->post("/login", [LoginController::class, 'login']);
    });
    # 帳本成員
    Route::name("wallet.detail.user")->post("/wallet/{wallet}/detail/user", [WalletDetailController::class, 'user']);
});
Route::fallback(function () {
    return response([
        'code'    => 400,
        'status'  => false,
        'message' => '不支援此方法',
    ]);
});
