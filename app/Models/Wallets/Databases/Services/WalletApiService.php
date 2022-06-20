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

class WalletApiService extends Service
{
    protected function getEntity(): Model
    {
        // TODO: Implement getEntity() method.
        if (app()->has(WalletEntity::class) === false) {
            app()->singleton(WalletEntity::class);
        }

        return app(WalletEntity::class);
    }

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
}
