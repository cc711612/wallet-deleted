<?php
/**
 * @Author: Roy
 * @DateTime: 2022/12/26 下午 03:35
 */

namespace Tests\Traits;

use Illuminate\Support\Str;

trait AuthTrait
{
    private $member_password = 'testing000';
    private $member_account;

    public function getRegister()
    {
        return $this->post(route('api.auth.register'), [
            "account"  => $this->member_account,
            "password" => $this->member_password,
            "name"     => Str::random('5'),
        ]);
    }

    public function getLogin()
    {
        return $this->post(route('api.auth.login'), [
            "account"  => $this->member_account,
            "password" => $this->member_password,
        ]);
    }

    public function getLogout($member_token = null)
    {
        return $this->post(route('api.auth.logout'), [
            "member_token" => is_null($member_token) ? Str::random(10) : $member_token,
        ]);
    }
}
