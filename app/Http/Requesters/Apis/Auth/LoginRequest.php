<?php

namespace App\Http\Requesters\Apis\Auth;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;

class LoginRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'account'        => null,
            'users.account'  => null,
            'password'       => null,
            'users.password' => null,
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
            'account'        => Arr::get($row, 'account'),
            'users.account'  => Arr::get($row, 'account'),
            'password'       => Arr::get($row, 'password'),
            'users.password' => Arr::get($row, 'password'),
        ];
    }

}
