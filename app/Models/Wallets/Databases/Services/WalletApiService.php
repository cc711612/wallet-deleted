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

class WalletApiService extends Service
{
    use WalletUserAuthCacheTrait;

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
            ->select(['id', 'user_id', 'title', 'code', 'status', 'updated_at']);

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
        return $this->getEntity()
            ->with([
                WalletDetailEntity::Table => function ($queryDetail) {
                    return $queryDetail;
                },
            ])
            ->find($this->getRequestByKey('wallets.id'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 01:11
     */
    public function getWalletWithUser()
    {
        if (is_null($this->getRequestByKey('wallets.id'))) {
            return null;
        }
        return $this->getEntity()
            ->with([
                WalletUserEntity::Table,
            ])
            ->find($this->getRequestByKey('wallets.id'));
    }

    /**
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 02:21
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
}
