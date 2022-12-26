<?php

namespace Tests\Unit;

use Tests\TestCase;
use Database\Seeders\TestUserWalletSeeder;
use Tests\Traits\AuthTrait;
use Tests\Traits\ResponseTrait;
use Illuminate\Support\Arr;
use Tests\Traits\WalletTrait;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Http\Response as HttpResponse;

class WalletTest extends TestCase
{
    use WalletTrait, AuthTrait, ResponseTrait;

    private $member_data;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member_account = config('testing.user.account');
        $this->member_password = config('testing.user.password');
    }

    public function test_get_wallet_list()
    {
        $this->seed(TestUserWalletSeeder::class);
        $this->member_token = $this->getMemberToken();
        $response = $this->getWalletList();
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->has('data.paginate')
                ->has('data.wallets')
                ->etc();
        });
        $response->assertOk();
    }

    public function test_get_wallet_user()
    {
        $this->member_token = $this->getMemberToken();
        $response = $this->getWalletUser($this->getMemberDataByKey('wallet.code'));
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->has('data.wallet.users')
                ->etc();
        });
        $response->assertOk();
    }

    public function test_store_wallet()
    {
        $this->member_token = $this->getMemberToken();
        $response = $this->storeWallet();
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->has('data.wallet.id')
                ->etc();
        });
        $response->assertOk();
    }

    public function test_update_wallet()
    {
        $this->member_token = $this->getMemberToken();
        $ResponseList = $this->getWalletList();
        $ResponseList->assertOk();
        $wallet_id = Arr::get($this->getContentToArray($ResponseList), 'data.wallets.0.id');
        $response = $this->updateWallet($wallet_id);
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('status', true)
                ->where('code', HttpResponse::HTTP_OK)
                ->etc();
        });
        $response->assertOk();
    }
}
