<?php
/**
 * @Author: Roy
 * @DateTime: 2022/12/26 下午 04:08
 */

namespace Tests\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

trait WalletTrait
{
    private $member_token;

    public function getWalletList()
    {
        return $this->post(route('api.wallet.index'), [
            "member_token" => $this->member_token,
        ]);
    }

    public function getWalletUser($code)
    {
        return $this->post(route('api.wallet.user'), [
            "code" => $code,
        ]);
    }

    public function storeWallet()
    {
        return $this->post(route('api.wallet.store'), [
            "title"        => Str::random('10'),
            "member_token" => $this->member_token,
        ]);
    }

    public function updateWallet($wallet_id)
    {
        return $this->put(route('api.wallet.update', [
            'wallet' => $wallet_id,
        ]), [
            "title"        => Str::random('10'),
            "member_token" => $this->member_token,
        ]);
    }

    public function getWalletDetail($wallet_id)
    {
        return $this->post(route('api.wallet.detail.index', [
            'wallet' => $wallet_id,
        ]), [
            "member_token" => $this->member_token,
        ]);
    }

    public function storeWalletDetail($wallet_id)
    {
        $type = rand(1, 2);
        return $this->post(route('api.wallet.detail.store', [
            'wallet' => $wallet_id,
        ]), [
            "member_token"             => $this->member_token,
            "type"                     => $type,
            "title"                    => Str::random('10'),
            "value"                    => rand(100, 10000),
            "select_all"               => 1,
            "payment_wallet_user_id"   => null,
            "symbol_operation_type_id" => $type,
            "users"                    => [],
        ]);
    }

    public function updateWalletDetail($wallet_id, $wallet_detail_id)
    {
        $type = rand(1, 2);
        return $this->put(route('api.wallet.detail.update', [
            'wallet' => $wallet_id,
            'detail' => $wallet_detail_id,
        ]), [
            "member_token"             => $this->member_token,
            "title"                    => Str::random('10'),
            "value"                    => rand(100, 10000),
            "select_all"               => 1,
            "payment_wallet_user_id"   => null,
            "symbol_operation_type_id" => $type,
            "users"                    => [],
        ]);
    }

    public function deleteWalletDetail($wallet_id, $wallet_detail_id)
    {
        return $this->delete(route('api.wallet.detail.destroy', [
            'wallet' => $wallet_id,
            'detail' => $wallet_detail_id,
        ]), [
            "member_token"             => $this->member_token,
        ]);
    }

    public function getMemberToken()
    {
        return $this->getMemberDataByKey('member_token');
    }

    public function getMemberDataByKey($key)
    {
        if (is_null($this->member_data)) {
            $login = $this->getLogin();
            $login->assertOk();
            $this->member_data = $this->getContentToArray($login);
        }

        return Arr::get($this->member_data, 'data.'.$key);
    }
}
