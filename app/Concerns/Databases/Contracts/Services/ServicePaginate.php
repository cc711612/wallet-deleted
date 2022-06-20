<?php
/**
 * @Author  : daniel
 * @DateTime: 2019-01-29 10:45
 */

namespace App\Concerns\Databases\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface ServicePaginate
 *
 * @package App\Concerns\Databases\Contracts\Services
 * @Author  : daniel
 * @DateTime: 2019-03-20 13:51
 */
interface ServicePaginate
{
    /**
     * @param int $page_count
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @Author  : daniel
     * @DateTime: 2019-03-18 15:56
     */
    public function paginate(int $page_count): LengthAwarePaginator;
}