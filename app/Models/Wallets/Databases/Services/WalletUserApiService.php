<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/21 下午 02:42
 */

namespace App\Models\Wallets\Databases\Services;

use App\Concerns\Databases\Service;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\Databases\Entities\WalletUserEntity;

class WalletUserApiService extends Service
{
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
}
