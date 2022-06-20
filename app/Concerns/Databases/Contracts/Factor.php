<?php

namespace App\Concerns\Databases\Contracts;

/**
 * Interface Display
 *
 * @package App\Databases\Contracts
 * @Author  : daniel
 * @DateTime: 2019-02-18 15:52
 */
interface Factor
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @Author  : ljs
     * @DateTime: 2019/2/23 下午 5:45
     */
    public function getBuilder();
}