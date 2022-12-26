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

    protected function setUp(): void
    {
        parent::setUp();
        $this->member_account = config('testing.user.account');
        $this->member_password = config('testing.user.password');
    }

    public function test_get_wallet_detail()
    {
        $this->member_token = $this->getMemberToken();
        $response = $this->getWalletDetail($this->getMemberDataByKey('wallet.id'));
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->has('data.wallet')
                ->has('data.wallet.details')
                ->etc();
        });
        $response->assertOk();
    }

    public function test_store_wallet_detail()
    {
        $this->member_token = $this->getMemberToken();
        $response = $this->storeWalletDetail($this->getMemberDataByKey('wallet.id'));
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->has('data')
                ->etc();
        });
        $response->assertOk();
    }

    public function test_update_wallet_detail()
    {
        $this->member_token = $this->getMemberToken();
        $WalletDetail = $this->getWalletDetail($this->getMemberDataByKey('wallet.id'));
        $WalletDetail->assertOk();
        $wallet_id = Arr::get($this->getContentToArray($WalletDetail), 'data.wallet.id');
        $detail_id = Arr::get($this->getContentToArray($WalletDetail), 'data.wallet.details.0.id');
        $response = $this->updateWalletDetail($wallet_id, $detail_id);
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->has('data')
                ->etc();
        });
        $response->assertOk();
    }

    public function test_delete_wallet_detail()
    {
        $this->member_token = $this->getMemberToken();
        $WalletDetail = $this->getWalletDetail($this->getMemberDataByKey('wallet.id'));
        $WalletDetail->assertOk();
        $wallet_id = Arr::get($this->getContentToArray($WalletDetail), 'data.wallet.id');
        $detail_id = Arr::get($this->getContentToArray($WalletDetail), 'data.wallet.details.0.id');
        $response = $this->deleteWalletDetail($wallet_id, $detail_id);
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->etc();
        });
        $response->assertOk();
    }
}
