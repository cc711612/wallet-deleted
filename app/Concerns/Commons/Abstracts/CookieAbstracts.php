<?php
namespace App\Concerns\Commons\Abstracts;
use Cookie;
/**
 * Class CookieAbstracts
 *
 * @package App\Concerns\Commons\Abstracts
 * @Author  : daniel
 * @DateTime: 2020-05-08 17:41
 */
abstract class CookieAbstracts
{
    /**
     * @var int
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    protected $expired_minutes = 14400;
    /**
     * @return bool
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function has()
    {
        return Cookie::has(static::COOKIE_NAME);
    }
    /**
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function create()
    {
        if ($this->expired_minutes > 0) {
            Cookie::queue(Cookie::make(static::COOKIE_NAME, $this->genValue(), $this->expired_minutes));
        } else {
            Cookie::queue(Cookie::forever(static::COOKIE_NAME, $this->genValue()));
        }
    }
    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    abstract public function genValue();
    /**
     * @return array|string|null
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function get()
    {
        return Cookie::get(static::COOKIE_NAME);
    }
    /**
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function destroy()
    {
        Cookie::queue(Cookie::forget(static::COOKIE_NAME));
    }
}
