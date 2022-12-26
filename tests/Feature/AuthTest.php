<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Tests\Traits\AuthTrait;
use Tests\Traits\ResponseTrait;

class AuthTest extends TestCase
{
    use AuthTrait, ResponseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member_account = sprintf("testing_integration_account_%s", Carbon::now()->getTimestamp());
    }

    public function test_auth_register_login_logout()
    {
        # 註冊
        $register = $this->getRegister();
        $register->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->etc();
        });
        $register->assertOk();
        # 登入
        $login = $this->getLogin();
        $login->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->has('data')
                ->has('data.id')
                ->has('data.name')
                ->has('data.member_token')
                ->has('data.wallet')
                ->etc();
        });
        $login->assertOk();
        # 登出
        $logout = $this->getLogout(Arr::get($this->getContentToArray($login), 'data.member_token'));
        $logout->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->etc();
        });
        $logout->assertOk();
    }
}
