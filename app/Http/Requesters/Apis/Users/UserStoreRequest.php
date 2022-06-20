<?php

namespace App\Http\Requesters\Apis\Users;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;

class UserStoreRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'name' => null,
            'password' => null,
            'email' => null,
            'introduction' => null,
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
            'name' => Arr::get($row, 'name'),
            'password' => Arr::get($row, 'password'),
            'email' => Arr::get($row, 'email'),
            'introduction' => Arr::get($row, 'introduction'),
        ];
    }

}
