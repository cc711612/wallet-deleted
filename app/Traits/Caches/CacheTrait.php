<?php
/**
 * @Author: Roy
 * @DateTime: 2021/8/12 下午 09:04
 */

namespace App\Traits\Caches;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Users\Databases\Entities\UserEntity;

trait CacheTrait
{

    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2022/7/10 下午 07:53
     */
    public function getCacheKeyFormat(): string
    {
        return config('cache_key.wallet_user');
    }

    /**
     * @param $code
     *
     * @return bool
     * @Author: Roy
     * @DateTime: 2022/7/10 下午 08:05
     */
    public function forgetCache($code)
    {
        $CacheKey = sprintf($this->getCacheKeyFormat(), $code);
        # Cache

        if (Cache::has($CacheKey) === true) {
            return Cache::forget($CacheKey);
        }
        return false;
    }

    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2022/7/17 下午 01:28
     */
    public function getDetailCacheKeyFormat(): string
    {
        return config('cache_key.wallet_details');
    }

    /**
     * @param $code
     *
     * @return bool
     * @Author: Roy
     * @DateTime: 2022/7/10 下午 08:05
     */
    public function forgetDetailCache($id)
    {
        $CacheKey = sprintf($this->getDetailCacheKeyFormat(), $id);
        # Cache

        if (Cache::has($CacheKey) === true) {
            return Cache::forget($CacheKey);
        }
        return false;
    }
}

