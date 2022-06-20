<?php

namespace App\Concerns\Databases\Contracts;

/**
 * Interface Cache
 *
 * @package App\Databases\Contracts
 * @Author  : daniel
 * @DateTime: 2019-03-19 11:31
 */
interface Cache
{
    /**
     * @param array $tag_list
     * @param bool  $reset
     *
     * @return \App\Concerns\Databases\Contracts\Cache
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function tags(array $tag_list, bool $reset = false): Cache;

    /**
     * @return bool
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function has() : bool;

    /**
     * @return bool
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function isEnableCache(): bool;

    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function getTagList(): array;

    /**
     * @return string
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function getKey(): string;

    /**
     * @param          $value
     * @param int|null $expiration_minutes
     *
     * @return bool
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function add($value, int $expiration_minutes = null):bool;

    /**
     * @return int
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function getExpirationMinutes(): int;

    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function display() : array ;

    /**
     * @param int $numeric
     *
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function increment(int $numeric = 0);

    /**
     * @param          $value
     * @param int|null $expiration_minutes
     *
     * @return array
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function put($value, int $expiration_minutes = null) : array;

    /**
     * @param $value
     *
     * @return array
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function forever($value) : array;

    /**
     * @param bool|null $intact
     *
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function get(bool $intact = null);

    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-07-12 11:59
     */
    public function flush();
}