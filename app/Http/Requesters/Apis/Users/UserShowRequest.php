<?php

namespace App\Http\Requesters\Apis\Users;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;

class UserShowRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'id'     => null,
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
            'id'     => Arr::get($row, 'id'),
        ];
    }

}
