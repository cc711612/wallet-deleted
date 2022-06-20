<?php

namespace App\Concerns\Databases\Contracts;

/**
 * Interface Request
 *
 * @package App\Elasticsearches\Contracts
 * @Author  : boday
 * @DateTime: 2018-12-28 20:22
 */
interface Request
{
    /**
     * @return bool
     * @Author  : boday
     * @DateTime: 2019-01-14 11:12
     */
    public function isCollection(): bool;

    /**
     * @return array
     * @Author  : boday
     * @DateTime: 2018-12-28 21:17
     */
    public function toArray(): array;

}
