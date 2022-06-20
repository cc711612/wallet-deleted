<?php
/**
 * @Author  : daniel
 * @DateTime: 2019-01-29 10:45
 */

namespace App\Concerns\Databases\Contracts\Services;

/**
 * Interface ServiceDraft
 *
 * @package App\Concerns\Databases\Contracts\Services
 * @Author  : daniel
 * @DateTime: 2019-03-20 14:19
 */
interface ServiceDraft
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @Author  : daniel
     * @DateTime: 2019-07-16 11:54
     */
    public function getDraft();
}