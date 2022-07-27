<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:53
 */

namespace App\Http\Controllers\Apis\Wallets\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requesters\Apis\Wallets\Auth\RegisterRequest;
use App\Http\Validators\Apis\Wallets\Auth\RegisterValidator;
use function response;
use App\Models\Wallets\Databases\Services\WalletUserApiService;
use App\Traits\Wallets\Auth\WalletUserAuthLoginTrait;
use App\Models\Wallets\Databases\Services\WalletApiService;
use App\Jobs\WalletUserRegister;


/**
 * Class WalletRegisterController
 *
 * @package App\Http\Controllers\Apis\Wallets
 * @Author: Roy
 * @DateTime: 2022/6/21 下午 12:05
 */
class WalletRegisterController extends Controller
{
    use WalletUserAuthLoginTrait;

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/21 下午 03:25
     */
    public function register(Request $request)
    {
        $requester = (new RegisterRequest($request));

        $Wallet = (new WalletApiService())
            ->setRequest($requester->toArray())
            ->getWalletByCode();

        $requester->__set('wallets.id', is_null($Wallet) ? null : $Wallet->id);

        $Validate = (new RegisterValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
            ]);
        }

        $UserEntity = (new WalletApiService())
            ->setRequest($requester->toArray())
            ->createWalletUserById();

        if (is_null($UserEntity)) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => "系統錯誤",
            ]);
        }
        WalletUserRegister::dispatch(
            [
                'wallet_user,' => $UserEntity,
                'wallet'       => $Wallet,
            ]
        );
        # set cache
        $this->setMemberTokenCache($UserEntity);

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => null,
            'data'    => [
                'id'           => Arr::get($UserEntity, 'id'),
                'name'         => Arr::get($UserEntity, 'name'),
                'member_token' => Arr::get($UserEntity, 'token'),
                'wallet'       => [
                    'id'   => Arr::get($Wallet, 'id'),
                    'code' => Arr::get($Wallet, 'code'),
                ],
            ],
        ]);
    }
}
