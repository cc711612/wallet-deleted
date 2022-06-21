<?php

namespace App\Http\Requesters\Apis\Wallets\Auth;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RegisterRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'name'               => null,
            'wallets.code'       => null,
            'wallets.id'         => null,
            'wallet_users.name'  => null,
            'wallet_users.token' => null,
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
            'name'               => Arr::get($row, 'name'),
            'wallets.id'         => Arr::get($row, 'wallet'),
            'wallets.code'       => Arr::get($row, 'code'),
            'wallet_users.name'  => Arr::get($row, 'name'),
            'wallet_users.token' => Str::random(12),
        ];
    }

}
