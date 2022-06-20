<?php
/**
 * @Author: Roy
 * @DateTime: 2021/8/12 下午 09:04
 */

namespace App\Traits\Wallets\Auth;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Users\Databases\Entities\UserEntity;

trait WalletUserAuthCacheTrait
{
    /**
     * @return $this
     * @Author: Roy
     * @DateTime: 2021/8/12 下午 09:13
     */
    private function updateWalletUserCache($user_id)
    {

        try {
            $user = (new UserEntity())->find($user_id);
            # 檢查token
            if ($this->checkToken($user->token)) {
                # 清除cache
                $this->cleanToken($user->token);
            }
            $cache = Cache::add(sprintf(config('cache_key.api.wallet_member_token'), $user->token),
                $user->wallet_users()->get()->keyBy('wallet_id'),
                config('app.login_timeout'));
            if ($cache === true) {
                Log::channel('token')->info(sprintf("Login info : %s ", json_encode([
                    'user_id'   => $user->id,
                    'cache_key' => sprintf(config('cache_key.api.wallet_member_token'), $user->token),
                    'token'     => $user->token,
                    'end_time'  => Carbon::now()->addSeconds(config('app.login_timeout'))->toDateTimeString(),
                ])));
            };
        } catch (\Throwable $exception) {
            Log::channel('error')->info(sprintf("Login errors : %s ", json_encode($exception, JSON_UNESCAPED_UNICODE)));
        }
        return $this;
    }

    /**
     * @param  string|null  $token
     *
     * @return bool
     * @Author: Roy
     * @DateTime: 2021/8/13 上午 10:44
     */
    private function checkToken(string $token)
    {
        return Cache::has($this->getCacheKey($token));
    }

    /**
     * @return bool
     * @Author: Roy
     * @DateTime: 2021/8/13 上午 10:36
     */
    private function cleanToken(string $token)
    {
        Log::channel('token')->info(sprintf("WalletUserToken Clean info : %s ", $this->getCacheKey($token)));
        return Cache::forget($this->getCacheKey($token));
    }

    /**
     * @param  string|null  $token
     *
     * @Author: Roy
     * @DateTime: 2021/8/13 上午 10:47
     */
    private function getCacheKey(string $token)
    {
        return sprintf(config('cache_key.api.wallet_member_token'), $token);
    }
}

