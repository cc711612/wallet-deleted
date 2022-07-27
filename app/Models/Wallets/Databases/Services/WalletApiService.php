<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 03:40
 */

namespace App\Models\Wallets\Databases\Services;

use App\Concerns\Databases\Service;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\Databases\Entities\WalletEntity;
use App\Models\Users\Databases\Entities\UserEntity;
use App\Models\Wallets\Databases\Entities\WalletDetailEntity;
use App\Models\Wallets\Databases\Entities\WalletUserEntity;
use Illuminate\Support\Facades\DB;
use App\Traits\Wallets\Auth\WalletUserAuthCacheTrait;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class WalletApiService extends Service
{
    use WalletUserAuthCacheTrait;

    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2022/7/10 下午 07:53
     */
    public function getCacheKeyFormat(): string
    {
        return "wallet_user.%s";
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
        return "wallet.details.%s";
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

    /**
     * @return \Illuminate\Database\Eloquent\Model
     * @Author: Roy
     * @DateTime: 2022/7/10 下午 08:05
     */
    protected function getEntity(): Model
    {
        // TODO: Implement getEntity() method.
        if (app()->has(WalletEntity::class) === false) {
            app()->singleton(WalletEntity::class);
        }

        return app(WalletEntity::class);
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 12:30
     */
    public function paginate()
    {
        $page_count = $this->getPageCount();

        # 一頁幾個
        if (is_null($this->getRequestByKey('per_page')) === false) {
            $page_count = $this->getRequestByKey('per_page');
        }

        $Result = $this->getEntity()
            ->with([
                UserEntity::Table => function ($query) {
                    $query->select(['id', 'name']);
                },
            ])
            ->where('user_id', $this->getRequestByKey('users.id'))
            ->select(['id', 'user_id', 'title', 'code', 'status', 'updated_at', 'created_at']);

        return $Result
            ->where('status', 1)
            ->orderByDesc('updated_at')
            ->paginate($page_count);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 12:32
     */
    public function getWalletWithDetail()
    {
        if (is_null($this->getRequestByKey('wallets.id'))) {
            return null;
        }
        $CacheKey = sprintf($this->getDetailCacheKeyFormat(), $this->getRequestByKey('wallets.id'));
        # Cache
        if (Cache::has($CacheKey) === true) {
            return Cache::get($CacheKey);
        }

        $Result = $this->getEntity()
            ->with([
                WalletDetailEntity::Table => function ($queryDetail) {
                    return $queryDetail->with([
                        WalletUserEntity::Table,
                    ]);
                },
                WalletUserEntity::Table,
            ])
            ->find($this->getRequestByKey('wallets.id'));

        Cache::add($CacheKey, $Result, 604800);

        return $Result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 01:11
     */
    public function getWalletWithUserByCode()
    {
        $CacheKey = sprintf($this->getCacheKeyFormat(), $this->getRequestByKey('wallets.code'));
        # Cache

        if (Cache::has($CacheKey) === true) {
            return Cache::get($CacheKey);
        }
        $Result = $this->getEntity()
            ->with([
                WalletUserEntity::Table,
            ])
            ->where('code', $this->getRequestByKey('wallets.code'))
            ->first();

        Cache::add($CacheKey, $Result, 604800);
        return $Result;
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @Author: Roy
     * @DateTime: 2022/7/10 下午 09:42
     */
    public function createWalletWithUser()
    {
        return DB::transaction(function () {
            $Entity = $this->getEntity()
                ->create($this->getRequestByKey('wallets'));
            $Entity->wallet_users()->create($this->getRequestByKey('wallet_users'));
            $this->updateWalletUserCache($this->getRequestByKey('wallets.user_id'));
            return $Entity;
        });
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @Author: Roy
     * @DateTime: 2022/7/10 下午 09:42
     */
    public function createWalletUserById()
    {
        return DB::transaction(function () {
            $Entity = $this->getEntity()
                ->find($this->getRequestByKey('wallets.id'));

            if (is_null($Entity)) {
                return null;
            }
            $this->forgetCache(Arr::get($Entity, 'code'));
            return $Entity->wallet_users()->create($this->getRequestByKey('wallet_users'));
        });
    }

    /**
     * @return null
     * @Author: Roy
     * @DateTime: 2022/7/10 下午 09:42
     */
    public function getWalletByCode()
    {
        if (is_null($this->getRequestByKey('wallets.code'))) {
            return null;
        }
        return $this->getEntity()
            ->where('status', 1)
            ->where('code', $this->getRequestByKey('wallets.code'))
            ->first();
    }

    /**
     * @param  array  $create
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @Author: Roy
     * @DateTime: 2022/6/22 上午 12:04
     */
    public function createWalletDetail()
    {
        if (is_null($this->getRequestByKey('wallets.id'))) {
            return null;
        }
        return DB::transaction(function () {
            $Entity = $this->getEntity()
                ->find($this->getRequestByKey('wallets.id'));
            if (is_null($Entity) === true) {
                return null;
            }
            $DetailEntity = $Entity->wallet_details()->create($this->getRequestByKey('wallet_details'));
            # 不等於公帳
            if ($this->getRequestByKey('wallet_details.type') != WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE) {
                $Users = $this->getRequestByKey('wallet_detail_wallet_user');
                # 全選
                if ($this->getRequestByKey('wallet_details.select_all') == 1) {
                    $Users = $Entity->wallet_users()->get()->pluck('id')->toArray();
                }
                $DetailEntity->wallet_users()->sync($Users);
            }
            return $DetailEntity;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @Author: Roy
     * @DateTime: 2022/7/5 上午 10:34
     */
    public function getWalletUsersAndDetails()
    {
        if (is_null($this->getRequestByKey('wallets.id'))) {
            return null;
        }
        return $this->getEntity()
            ->with([
                WalletDetailEntity::Table => function ($queryDetail) {
                    return $queryDetail->with([
                        WalletUserEntity::Table,
                    ]);
                },
                WalletUserEntity::Table,
            ])
            ->find($this->getRequestByKey('wallets.id'));
    }
}
