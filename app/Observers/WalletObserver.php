<?php

namespace App\Observers;

use App\Models\Wallets\Databases\Entities\WalletEntity;
use App\Models\Wallets\Databases\Services\WalletApiService;

/**
 * Class WalletObserver
 *
 * @package App\Observers
 * @Author: Roy
 * @DateTime: 2022/7/17 下午 01:25
 */
class WalletObserver
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
     * @param  \App\Models\Wallets\Databases\Entities\WalletEntity  $WalletEntity
     *
     * @return bool
     * @Author: Roy
     * @DateTime: 2022/7/17 下午 01:39
     */
    public function created(WalletEntity $WalletEntity)
    {

    }

    /**
     * Handle the WalletEntity "updated" event.
     *
     * @param  \App\Models\Wallets\Databases\Entities\WalletEntity  $WalletEntity
     *
     * @return void
     */
    public function updated(WalletEntity $WalletEntity)
    {
        //
        return $this->wallet_api_service->forgetDetailCache($WalletEntity->id);
    }

    /**
     * Handle the WalletEntity "deleted" event.
     *
     * @param  \App\Models\Wallets\Databases\Entities\WalletEntity  $WalletEntity
     *
     * @return void
     */
    public function deleted(WalletEntity $WalletEntity)
    {
        //
    }

    /**
     * Handle the WalletEntity "restored" event.
     *
     * @param  \App\Models\Wallets\Databases\Entities\WalletEntity  $WalletEntity
     *
     * @return void
     */
    public function restored(WalletEntity $WalletEntity)
    {
        //
    }

    /**
     * Handle the WalletEntity "force deleted" event.
     *
     * @param  \App\Models\Wallets\Databases\Entities\WalletEntity  $WalletEntity
     *
     * @return void
     */
    public function forceDeleted(WalletEntity $WalletEntity)
    {
        //
    }
}
