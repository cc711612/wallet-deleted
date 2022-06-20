<?php

namespace App\Http\Middleware;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Traits\AuthLoginTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Traits\Wallets\Auth\WalletUserAuthLoginTrait;

class VerifyWalletMemberApi
{
    use WalletUserAuthLoginTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     *
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = null)
    {
        $member_token = $request->member_token;

        if ($member_token == null) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => ['member_token' => ['請帶入 member_token']],
                'data'    => [],
            ]);
        }

        if ($this->checkToken($member_token) === false) {
            Log::channel('token')->info(sprintf("Verify token is empty info : %s ", $request->member_token));
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => ['member_token' => ['請重新登入']],
                'data'    => [],
            ]);
        }
        # 取得快取資料
        $LoginCache = Cache::get($this->getCacheKey($member_token));

        # 若有新增請記得至 ResponseApiServiceProvider 排除
        $request->merge([
            'wallet_user' => $LoginCache,
        ]);

        return $next($request);
    }
}
