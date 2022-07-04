<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 03:40
 */

namespace App\Models\Wallets\Databases\Services;

use App\Concerns\Databases\Service;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\Databases\Entities\WalletEntity;
use App\Models\Wallets\Databases\Entities\WalletDetailEntity;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use Illuminate\Support\Arr;

class WalletDetailJobService extends Service
{
    protected function getEntity(): Model
    {
        // TODO: Implement getEntity() method.
        if (app()->has(WalletDetailEntity::class) === false) {
            app()->singleton(WalletDetailEntity::class);
        }

        return app(WalletDetailEntity::class);
    }

    /**
     * @param $wallet_id
     *
     * @return mixed
     * @throws \Throwable
     * @Author: Roy
     * @DateTime: 2022/7/4 下午 11:22
     */
    public function updateAllSelectedWalletDetails($wallet_id)
    {
        return DB::transaction(function () use ($wallet_id) {
            $WalletUsers = (new WalletUserApiService())
                ->getWalletUserByWalletId($wallet_id)->pluck('id')->toArray();
            $Details = $this->getEntity()
                ->where('wallet_id', $wallet_id)
                ->where('select_all', 1)
                ->get();
            return $Details->map(function (WalletDetailEntity $Detail) use ($WalletUsers) {
                return $Detail->wallet_users()->sync($WalletUsers);
            });
        });
    }
}
