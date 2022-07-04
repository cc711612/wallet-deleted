<?php

namespace App\Http\Requesters\Apis\Wallets\Users;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class WalletUserDestroyRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'wallets.id'              => null,
            'wallet_users.id'         => null,
            'wallet_users.deleted_by' => null,
            'wallet_users.deleted_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * @param $row
     *
     * @return array
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function map($row): array
    {
        return [
            'wallets.id'                => Arr::get($row, 'wallet'),
            'wallet_users.id'           => Arr::get($row, 'wallet_user_id'),
            'wallet_users.deleted_by' => Arr::get($row,
                sprintf("wallet_user.%s.id", Arr::get($row, 'wallet'))),
        ];
    }
}
