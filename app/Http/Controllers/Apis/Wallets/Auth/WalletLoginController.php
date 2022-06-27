<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:53
 */

namespace App\Http\Controllers\Apis\Wallets\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requesters\Apis\Wallets\Auth\LoginRequest;
use App\Http\Validators\Apis\Wallets\Auth\LoginValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Traits\Wallets\Auth\WalletUserAuthLoginTrait;
use App\Models\Wallets\Databases\Services\WalletApiService;
use App\Models\Wallets\Databases\Services\WalletUserApiService;

/**
 * Class WalletLoginController
 *
 * @package App\Http\Controllers\Apis\Wallets\Auth
 * @Author: Roy
 * @DateTime: 2022/6/21 下午 12:08
 */
class WalletLoginController extends Controller
{
    use WalletUserAuthLoginTrait;

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/20 下午 03:16
     */
    public function login(Request $request)
    {
        $requester = (new LoginRequest($request));

        $Wallet = (new WalletApiService())
            ->setRequest($requester->toArray())
            ->getWalletByCode();

        $requester->__set('wallets.id', is_null($Wallet) ? null : $Wallet->id);
        $requester->__set('wallet_users.wallet_id', is_null($Wallet) ? null : $Wallet->id);

        $Validate = (new LoginValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
            ]);
        }
        $UserEntity = (new WalletUserApiService())
            ->setRequest($requester->toArray())
            ->getWalletUserByNameAndWalletId();

        if (is_null($UserEntity)) {
            return response()->json([
                'status'  => false,
                'code'    => 401,
                'message' => "系統錯誤",
            ]);
        }
        if ($UserEntity->is_admin == 1) {
            return response()->json([
                'status'  => false,
                'code'    => 401,
                'message' => "管理者不得使用此方式登入",
            ]);
        }
        # set cache
        $this->setMemberTokenCache($UserEntity);

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'id'           => Arr::get($UserEntity, 'id'),
                'name'         => Arr::get($UserEntity, 'name'),
                'wallet_id'    => Arr::get($UserEntity, 'wallet_id'),
                'member_token' => Arr::get($UserEntity, 'token'),
            ],
        ]);
    }
}
