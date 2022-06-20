<?php
/**
 * @Author  : daniel
 * @DateTime: 2019-01-29 10:45
 */

namespace App\Concerns\Databases\Contracts\Services;

/**
 * Interface ServiceCreatedBy
 *
 * @package App\Concerns\Databases\Contracts\Services
 * @Author  : ljs
 * @DateTime: 2019/9/19 上午 9:53
 */
interface ServiceCreatedBy
{
    /**
     * @param int $admin_id
     *
     * @return mixed
     * @Author  : ljs
     * @DateTime: 2019/9/19 上午 9:53
     */
    public function setAdminId(int $admin_id);
}