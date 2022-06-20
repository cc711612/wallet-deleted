<?php

namespace App\Concerns\Databases\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface Repository
 *
 * @package App\Databases\Contracts
 * @Author  : daniel
 * @DateTime: 2019-01-29 10:56
 */
interface Repository
{
    /**
     * @return \Illuminate\Support\Collection
     * @Author  : daniel
     * @DateTime: 2019-02-19 17:08
     */
    public function get(): Collection;

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     * @Author  : daniel
     * @DateTime: 2019-02-19 17:08
     */
    public function first();

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     * @Author  : daniel
     * @DateTime: 2019-02-19 17:08
     */
    public function last();

    /**
     * @param int $id
     *
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-03-19 17:24
     */
    public function find(int $id);

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @Author  : daniel
     * @DateTime: 2019-02-18 14:25
     */
    public function getBuilder();

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @Author  : daniel
     * @DateTime: 2019-03-13 10:31
     */
    public function getEntity();
    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2019-02-20 09:53
     */
    public function getRelation():array ;

    /**
     * @param array $Relation
     *
     * @return \App\Concerns\Databases\Contracts\Repository
     * @Author  : daniel
     * @DateTime: 2019-07-16 11:50
     */
    public function setRelation(array $Relation):self ;

    /**
     * @param array $Select
     *
     * @return \App\Concerns\Databases\Contracts\Repository
     * @Author  : daniel
     * @DateTime: 2019-07-16 11:50
     */
    public function setSelect(array $Select):self;

    /**
     * @param array $Request
     *
     * @return $this
     * @Author  : daniel
     * @DateTime: 2020/9/29 11:21 上午
     */
    public function setRequest(array $Request):self;
    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2019-07-16 11:50
     */
    public function getSelect(): array;

    /**
     * @param string $order_by_raw
     *
     * @return \App\Concerns\Databases\Contracts\Repository
     * @Author  : ljs
     * @DateTime: 2019/7/19 上午 10:29
     */
    public function setOrderByRaw(string $order_by_raw):self;

    /**
     * @param array $order_by
     *
     * @return \App\Concerns\Databases\Contracts\Repository
     * @Author  : ljs
     * @DateTime: 2019/7/19 上午 10:29
     */
    public function setOrderBy(array $order_by):self;
}
