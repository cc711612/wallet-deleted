<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/21 下午 02:42
 */

namespace App\Models\Wallets\Databases\Services;

use App\Concerns\Databases\Service;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\Databases\Entities\WalletUserEntity;
use App\Models\Wallets\Databases\Entities\WalletEntity;
use Illuminate\Support\Facades\DB;
use App\Traits\Caches\CacheTrait;
use Illuminate\Support\Arr;

class WalletUserApiService extends Service
{
    use CacheTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     * @Author: Roy
     * @DateTime: 2022/6/21 下午 02:43
     */
    protected function getEntity(): Model
    {
        // TODO: Implement getEntity() method.
        if (app()->has(WalletUserEntity::class) === false) {
            app()->singleton(WalletUserEntity::class);
        }

        return app(WalletUserEntity::class);
    }

    /**
     * @return null
     * @Author: Roy
     * @DateTime: 2022/7/4 下午 06:49
     */
    public function validateWalletUsers()
    {
        if (is_null($this->getRequestByKey('wallets.id'))) {
            return false;
        }

        return $this->getEntity()
                ->where('wallet_id', $this->getRequestByKey('wallets.id'))
                ->whereIn('id', $this->getRequestByKey('wallet_detail_wallet_user'))
                ->count() == count($this->getRequestByKey('wallet_detail_wallet_user'));
    }

    /**
     * @return null
     * @Author: Roy
     * @DateTime: 2022/6/21 下午 05:11
     */
    public function getWalletUserByNameAndWalletId()
    {
        if (is_null($this->getRequestByKey('wallet_users.wallet_id')) || is_null($this->getRequestByKey('wallet_users.name'))) {
            return null;
        }
        return $this->getEntity()
            ->where('wallet_id', $this->getRequestByKey('wallet_users.wallet_id'))
            ->where('name', $this->getRequestByKey('wallet_users.name'))
            ->first();
    }

    /**
     * @return null
     * @Author: Roy
     * @DateTime: 2022/6/28 上午 05:38
     */
    public function getWalletUserByTokenAndWalletId()
    {
        if (is_null($this->getRequestByKey('wallet_users.wallet_id')) || is_null($this->getRequestByKey('wallet_users.token'))) {
            return null;
        }
        return $this->getEntity()
            ->where('wallet_id', $this->getRequestByKey('wallet_users.wallet_id'))
            ->where('token', $this->getRequestByKey('wallet_users.token'))
            ->first();
    }

    /**
     * @return void|null
     * @Author: Roy
     * @DateTime: 2022/7/4 下午 06:23
     */
    public function getUserWithDetail()
    {
        return $this->getEntity()
            ->with([
                "created_wallet_details",
                "wallet_details",
            ])
            ->find($this->getRequestByKey('wallet_users.id'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @Author: Roy
     * @DateTime: 2022/7/4 下午 06:35
     */
    public function delete()
    {
        return DB::transaction(function () {
            $UserEntity = $this->getEntity()
                ->with([WalletEntity::Table])
                ->find($this->getRequestByKey('wallet_users.id'));

            if (is_null($UserEntity)) {
                return null;
            }

            $this->forgetCache(Arr::get($UserEntity, 'wallets.code'));
            return $UserEntity->update($this->getRequestByKey('wallet_users'));
        });
    }

    /**
     * @param $wallet_id
     *
     * @return mixed
     * @Author: Roy
     * @DateTime: 2022/7/4 下午 11:20
     */
    public function getWalletUserByWalletId($wallet_id)
    {
        return $this->getEntity()
            ->where('wallet_id', $wallet_id)
            ->get();
    }
}
