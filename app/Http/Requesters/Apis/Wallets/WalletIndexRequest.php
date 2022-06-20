<?php

namespace App\Http\Requesters\Apis\Wallets;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;

class WalletIndexRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'users.id' => null,
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
            'users.id' => Arr::get($row, 'user.id'),
        ];
    }
}
