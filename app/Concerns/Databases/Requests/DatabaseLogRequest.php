<?php

namespace App\Concerns\Databases\Requests;

use App\Concerns\Databases\Request;
use Arr;

/**
 * Class DatabaseRequest
 *
 * @package App\Concerns\Databases\Requests
 * @Author  : daniel
 * @DateTime: 2019-03-14 15:32
 */
class DatabaseLogRequest extends Request
{
    /**
     * @param $row
     *
     * @return array
     * @Author  : daniel
     * @DateTime: 2019-03-14 15:32
     */
    protected function map($row): array
    {
        return [
            'changes'      => Arr::get($row, 'changes'),
            'route_name'   => Arr::get($row, 'route_name'),
            'interface_by' => Arr::get($row, 'interface_by'),
            'updated_by'   => Arr::get($row, 'updated_by'),
        ];

    }

    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2019-03-14 15:32
     */
    protected function schema(): array
    {
        return [
            'changes'      => [],
            'route_name'   => \Route::currentRouteName(),
            'ipv4'         => ip2long(app('Illuminate\Http\Request')->ip()),
            'interface_by' => 1,
            'updated_by'   => null,
        ];
    }
}
