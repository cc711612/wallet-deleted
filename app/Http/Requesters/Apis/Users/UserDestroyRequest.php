<?php

namespace App\Http\Requesters\Apis\Users;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class UserDestroyRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'id'               => null,
            'users.deleted_at' => Carbon::now()->toDateTimeString(),
            'users.updated_by' => null,
            'users.deleted_by' => null,
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
            'id' => Arr::get($row, 'id'),
            'users.deleted_at' => Carbon::now()->toDateTimeString(),
            'users.deleted_by' => Arr::get($row, 'user.id'),
            'users.updated_by' => Arr::get($row, 'user.id'),
        ];
    }

}
