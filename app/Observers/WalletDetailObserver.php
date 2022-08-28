<?php

namespace App\Observers;

use App\Models\Wallets\Databases\Entities\WalletDetailEntity;
use App\Models\Wallets\Databases\Services\WalletApiService;
use Illuminate\Support\Carbon;

/**
 * Class WalletDetailObserver
 *
 * @package App\Observers
 * @Author: Roy
 * @DateTime: 2022/7/17 下午 01:25
 */
class WalletDetailObserver
{
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletApiService
     */
    private $wallet_api_service;

    /**
     * @param  \App\Models\Wallets\Databases\Services\WalletApiService  $WalletApiService
     */
    public function __construct(WalletApiService $WalletApiService)
    {
        $this->wallet_api_service = $WalletApiService;
    }

    /**
     * @param  \App\Models\Wallets\Databases\Entities\WalletDetailEntity  $WalletDetailEntity
     *
     * @return bool
     * @Author: Roy
     * @DateTime: 2022/7/17 下午 01:39
     */
    public function created(WalletDetailEntity $WalletDetailEntity)
    {
        $this->wallet_api_service->update(
            $WalletDetailEntity->wallet_id,
            ['updated_at' => Carbon::now()->toDateTimeString()]
        );
        return $this->wallet_api_service->forgetDetailCache($WalletDetailEntity->wallet_id);
    }

    /**
     * Handle the WalletDetailEntity "updated" event.
     *
     * @param  \App\Models\Wallets\Databases\Entities\WalletDetailEntity  $WalletDetailEntity
     *
     * @return void
     */
    public function updated(WalletDetailEntity $WalletDetailEntity)
    {
        //
        $this->wallet_api_service->update(
            $WalletDetailEntity->wallet_id,
            ['updated_at' => Carbon::now()->toDateTimeString()]
        );
        return $this->wallet_api_service->forgetDetailCache($WalletDetailEntity->wallet_id);
    }

    /**
     * Handle the WalletDetailEntity "deleted" event.
     *
     * @param  \App\Models\Wallets\Databases\Entities\WalletDetailEntity  $WalletDetailEntity
     *
     * @return void
     */
    public function deleted(WalletDetailEntity $WalletDetailEntity)
    {
        //
    }

    /**
     * Handle the WalletDetailEntity "restored" event.
     *
     * @param  \App\Models\Wallets\Databases\Entities\WalletDetailEntity  $WalletDetailEntity
     *
     * @return void
     */
    public function restored(WalletDetailEntity $WalletDetailEntity)
    {
        //
    }

    /**
     * Handle the WalletDetailEntity "force deleted" event.
     *
     * @param  \App\Models\Wallets\Databases\Entities\WalletDetailEntity  $WalletDetailEntity
     *
     * @return void
     */
    public function forceDeleted(WalletDetailEntity $WalletDetailEntity)
    {
        //
    }
}
