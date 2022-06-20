<?php
/**
 * @Author: Roy
 * @DateTime: 2021/8/12 下午 09:04
 */

namespace App\Traits;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait AuthLogoutTrait
{
    /**
     * @param  string|null  $token
     *
     * @return $this
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 02:30
     */
    private function cleanToken(string $token = null)
    {
        if(is_null($token) === false){
            if (Cache::has(sprintf(config('cache_key.api.member_token'), $token))) {
                # 清除cache
                Log::channel('token')->info(sprintf("Token Clean info : %s ",
                    sprintf(config('cache_key.api.member_token'), $token)));
                Cache::forget(sprintf(config('cache_key.api.member_token'), $token));
            }
        }
        return $this;
    }
}

