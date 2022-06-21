<?php

namespace App\Http\Requesters\Apis\Auth;

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
            'account'        => null,
            'users.account'  => null,
            'password'       => null,
            'users.password' => null,
            'name'           => null,
            'users.name'     => null,
            'users.token'    => null,
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
            'name'           => Arr::get($row, 'name'),
            'users.name'     => Arr::get($row, 'name'),
            'users.token'    => Str::random(12),
        ];
    }

}
