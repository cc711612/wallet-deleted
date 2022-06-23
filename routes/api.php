<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\LogoutController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\Apis\Wallets\WalletController;
use App\Http\Controllers\Apis\Wallets\WalletDetailController;
use App\Http\Controllers\Apis\Auth\RegisterController;
use App\Http\Controllers\Apis\Wallets\Auth\WalletRegisterController;
use App\Http\Controllers\Apis\Wallets\Auth\WalletLoginController;
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
    # 登入相關
    Route::group(['as' => 'auth.', 'namespace' => 'Auth', 'prefix' => 'auth'], function () {
        Route::name("login")->post("/login", [LoginController::class, 'login']);
        Route::name("register")->post("/register", [RegisterController::class, 'register']);
    });
    # 帳本成員
    Route::group(['as' => 'wallet.', 'prefix' => '/wallet'], function () {
        Route::name("user")->post("/user", [WalletController::class, 'user']);
        # 登入
        Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
            Route::name("login")->post("/login", [WalletLoginController::class, 'login']);
            Route::name("register")->post("/register", [WalletRegisterController::class, 'register']);
        });
    });
    # 需要member_token的
    Route::group(['middleware' => ['VerifyApi']], function () {
        # 登出相關
        Route::group(['as' => 'auth.', 'namespace' => 'Auth', 'prefix' => 'auth'], function () {
            Route::name("logout")->post("/logout", [LogoutController::class, 'logout']);
        });
        Route::group(['as' => 'wallet.', 'prefix' => 'wallet'], function () {
            Route::name("index")->post("/list", [WalletController::class, 'index']);
        });
        # 帳本
        Route::resource('wallet', WalletController::class)->only(['store', 'update', 'destroy']);
    });
    # 需要wallet_member_token的
    Route::group(['middleware' => ['VerifyWalletMemberApi']], function () {
        Route::group(['as' => 'wallet.', 'prefix' => 'wallet'], function () {
            # 帳本明細
            Route::group(['prefix' => '{wallet}'], function () {
                Route::resource('detail', WalletDetailController::class)->only(['store', 'update', 'destroy']);
                Route::name("detail.index")->post("/detail/list", [WalletDetailController::class, 'index']);
            });
        });
    });

});
Route::fallback(function () {
    return response([
        'code'    => 400,
        'status'  => false,
        'message' => '不支援此方法',
    ]);
});
