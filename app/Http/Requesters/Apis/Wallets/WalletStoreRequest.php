<?php

namespace App\Http\Requesters\Apis\Wallets;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class WalletStoreRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'users.id'        => null,
            'wallets.user_id' => null,
            'wallets.code'    => Str::random(8),
            'wallets.title'   => null,
            'wallets.status'  => 1,
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
            'users.id'        => Arr::get($row, 'user.id'),
            'wallets.user_id' => Arr::get($row, 'user.id'),
            'wallets.code'    => Str::random(8),
            'wallets.title'   => Arr::get($row, 'title'),
            'wallets.status'  => 1,
        ];
    }
}
