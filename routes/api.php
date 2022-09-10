<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\LogoutController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\Apis\Wallets\WalletController;
use App\Http\Controllers\Apis\Wallets\WalletDetailController;
use App\Http\Controllers\Apis\Wallets\WalletUserController;
use App\Http\Controllers\Apis\Auth\RegisterController;
use App\Http\Controllers\Apis\Wallets\Auth\WalletRegisterController;
use App\Http\Controllers\Apis\Wallets\Auth\WalletLoginController;
use App\Http\Controllers\Apis\Logs\LineController;
use App\Http\Controllers\Apis\Logs\FrontLogController;

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
        Route::name("cache")->match(['get', 'post'], "/cache", [LoginController::class, 'cache']);
        Route::name("register")->post("/register", [RegisterController::class, 'register']);
    });
    # 帳本成員
    Route::group(['as' => 'wallet.', 'prefix' => '/wallet'], function () {
        Route::name("user")->post("/user", [WalletUserController::class, 'index']);
        # 登入
        Route::group(['as' => 'auth.', 'prefix' => '/auth'], function () {
            Route::name("login")->post("/login", [WalletLoginController::class, 'login']);
            Route::name("login.token")->post("/login/token", [WalletLoginController::class, 'token']);
            Route::name("register")->post("/register", [WalletRegisterController::class, 'register']);
        });
    });
    # Webhook
    Route::group(['as' => 'webhook.', 'prefix' => '/webhook'], function () {
        Route::group(['as' => 'line.', 'prefix' => '/line'], function () {
            Route::name("store")->any("/", [LineController::class, 'store']);
        });
    });
    # Log
    Route::group(['as' => 'log.', 'prefix' => '/log'], function () {
        # Front
        Route::group(['as' => 'front.', 'prefix' => '/front'], function () {
            Route::name("info")->post("/normal", [FrontLogController::class, 'normal']);
            Route::name("critical")->post("/serious", [FrontLogController::class, 'serious']);
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
                Route::group(['prefix' => 'detail', 'as' => 'detail.'], function () {
                    Route::name("index")->post("/list", [WalletDetailController::class, 'index']);
                    Route::name("show")->post("/{detail}", [WalletDetailController::class, 'show']);
                    Route::name("checkout")->put("/checkout", [WalletDetailController::class, 'checkout']);
                    Route::name("uncheckout")->put("/undo_checkout", [WalletDetailController::class, 'uncheckout']);
                });
                Route::name("calculation")->post("/calculation", [WalletController::class, 'calculation']);
                Route::resource('detail', WalletDetailController::class)->only(['store', 'update', 'destroy']);
                # 帳本成員
                Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
                    Route::name("destroy")->delete("/{wallet_user_id}", [WalletUserController::class, 'destroy']);
                });
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
