<?php

namespace App\Concerns\Databases\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface Display
 *
 * @package App\Databases\Contracts
 * @Author  : daniel
 * @DateTime: 2019-02-18 15:52
 */
interface Relation
{
    public function __construct(Builder $Builder,\Closure $Closure);

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @Author  : daniel
     * @DateTime: 2019-02-18 15:55
     */
    public function getBuilder() : Builder;
}