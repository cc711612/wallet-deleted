<?php

namespace App\Http\Middleware;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Traits\AuthLoginTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class VerifyApi
{
    use AuthLoginTrait;

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
                'message' => '請帶入 member_token',
                'data'    => [],
            ]);
        }

        if ($this->checkToken($member_token) === false) {
            return response()->json($this->get_error_response($member_token));
        }
        # 取得快取資料
        $LoginCache = Cache::get($this->getCacheKey($member_token));
        if (is_null($LoginCache) === true) {
            return response()->json($this->get_error_response($member_token));
        }
        # 若有新增請記得至 ResponseApiServiceProvider 排除
        $request->merge([
            'user' => $LoginCache,
        ]);

        return $next($request);
    }

    private function get_error_response($token)
    {
        Log::channel('token')->info(sprintf("Verify token is empty info : %s ", $token));
        return [
            'status'  => false,
            'code'    => 401,
            'message' => "請重新登入",
            'data'    => [],
        ];
    }
}
