<?php

namespace App\Http\Requesters\Apis\Users;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;

class UserUpdateRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'id' => null,
            'name' => null,
//            'password' => null,
            'users.name' => null,
            'users.introduction' => null,
//            'users.password' => null,
            'users.images.cover' => null,
            'users.updated_by' => null,
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
            'users.name' => Arr::get($row, 'name'),
//            'password' => Arr::get($row, 'password'),
            'name' => Arr::get($row, 'name'),
//            'users.password' => Arr::get($row, 'password'),
            'users.images.cover' => Arr::get($row, 'image'),
            'users.introduction' => Arr::get($row, 'introduction'),
            'users.updated_by' => Arr::get($row, 'user.id'),
        ];
    }

}
