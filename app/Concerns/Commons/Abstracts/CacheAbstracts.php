<?php
namespace App\Concerns\Commons\Abstracts;
use Cache;

/**
 * Class CacheAbstracts
 *
 * @package App\Concerns\Commons\Abstracts
 * @Author  : daniel
 * @DateTime: 2020/8/19 9:38 上午
 */
abstract class CacheAbstracts
{
    /**
     * @var
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    protected $key;
    /**
     * @var
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    protected $params;
    /**
     * @var int
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    const EXPIRE_TIME = 86400; #24 * 60 * 60 seconds

    /**
     * @return $this
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    private function genKey()
    {
        if(empty($this->params)){
            throwException('no params');
        }

        $this->key = md5(http_build_query($this->getParams()));

        return $this;
    }

    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    public function getKey()
    {
        if(empty($this->key)){
            $this->genKey();
        }

        return $this->key;
    }

    /**
     * @param $key
     *
     * @return $this
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $params
     *
     * @return $this
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return bool
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    public function put()
    {
        return Cache::put($this->getKey(), $this->getParams(), static::EXPIRE_TIME);
    }

    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    public function get()
    {
        return Cache::get($this->getKey());
    }

    /**
     * @return bool
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    public function has()
    {
        return Cache::has($this->getKey());
    }

    /**
     * @return bool
     * @Author  : daniel
     * @DateTime: 2020/8/19 9:38 上午
     */
    public function forget()
    {
        return Cache::forget($this->getKey());
    }
}
