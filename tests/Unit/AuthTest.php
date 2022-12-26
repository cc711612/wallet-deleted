<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Carbon;
use Tests\Traits\AuthTrait;

class AuthTest extends TestCase
{
    use AuthTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member_account = sprintf("testing_unit_account_%s", Carbon::now()->getTimestamp());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_auth_cache()
    {
        $response = $this->get(route('api.auth.cache'));
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->etc();
        });
        $response->assertOk();
    }

    public function test_auth_register()
    {
        $response = $this->getRegister();
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->etc();
        });
        $response->assertOk();
    }

    public function test_auth_login()
    {
        $response = $this->getLogin();

        $response->assertJson(function (AssertableJson $json) {
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

        $response->assertOk();
    }

    public function test_auth_logout_fail()
    {
        $response = $this->getLogout();
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', false)
                ->where('code', HttpResponse::HTTP_UNAUTHORIZED)
                ->etc();
        });

        $response->assertOk();
    }
}
