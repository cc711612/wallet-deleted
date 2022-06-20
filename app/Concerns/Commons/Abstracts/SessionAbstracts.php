<?php
namespace App\Concerns\Commons\Abstracts;
use Session;
/**
 * Class SessionAbstracts
 *
 * @package App\Concerns\Commons\Abstracts
 * @Author  : daniel
 * @DateTime: 2020-05-08 17:40
 */
abstract class SessionAbstracts
{
    /**
     * @return bool
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:40
     */
    public function has()
    {
        return Session::has(static::SESSION_NAME);
    }
    /**
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:40
     */
    public function create()
    {
        Session::put(static::SESSION_NAME, $this->genValue());
    }
    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:40
     */
    abstract public function genValue();
    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:40
     */
    public function get()
    {
        return Session::get(static::SESSION_NAME);
    }
    /**
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:40
     */
    public function destroy()
    {
        Session::forget(static::SESSION_NAME);
    }
}