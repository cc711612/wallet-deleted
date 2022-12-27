<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Arr;
use Tests\Traits\WalletTrait;
use Tests\Traits\AuthTrait;
use Tests\Traits\ResponseTrait;

class WalletDetailTest extends TestCase
{
    use WalletTrait, AuthTrait, ResponseTrait;

    private $member_data;
    private $wallet_id = 1;
    private $wallet_detail_id = 1;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member_account = config('testing.user.account');
        $this->member_password = config('testing.user.password');
    }

    public function test_get_wallet_detail_fail()
    {
        $response = $this->getWalletDetail($this->wallet_id);
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', false)
                ->where('code', HttpResponse::HTTP_BAD_REQUEST)
                ->has('message')
                ->etc();
        });
        $response->assertOk();
    }

    public function test_store_wallet_detail_fail()
    {
        $response = $this->storeWalletDetail($this->wallet_id);
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', false)
                ->where('code', HttpResponse::HTTP_BAD_REQUEST)
                ->has('message')
                ->etc();
        });
        $response->assertOk();
    }

    public function test_update_wallet_detail_fail()
    {
        $wallet_id = $this->wallet_id;
        $detail_id = $this->wallet_detail_id;
        $response = $this->updateWalletDetail($wallet_id, $detail_id);
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', false)
                ->where('code', HttpResponse::HTTP_BAD_REQUEST)
                ->has('message')
                ->etc();
        });
        $response->assertOk();
    }

    public function test_delete_wallet_detail_fail()
    {
        $wallet_id = $this->wallet_id;
        $detail_id = $this->wallet_detail_id;
        $response = $this->deleteWalletDetail($wallet_id, $detail_id);
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', false)
                ->where('code', HttpResponse::HTTP_BAD_REQUEST)
                ->has('message')
                ->etc();
        });
        $response->assertOk();
    }
}
