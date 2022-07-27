<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 03:39
 */

namespace App\Http\Controllers\Apis\Wallets;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Wallets\Databases\Services\WalletApiService;
use Illuminate\Support\Arr;
use App\Traits\ApiPaginateTrait;
use App\Http\Requesters\Apis\Wallets\Users\WalletUserIndexRequest;
use App\Http\Validators\Apis\Wallets\Users\WalletUserIndexValidator;
use App\Http\Requesters\Apis\Wallets\Users\WalletUserDestroyRequest;
use App\Http\Validators\Apis\Wallets\Users\WalletUserDestroyValidator;
use App\Models\Wallets\Databases\Services\WalletUserApiService;

class WalletUserController extends Controller
{
    use ApiPaginateTrait;

    /**
     * @var \App\Models\Wallets\Databases\Services\WalletApiService
     */
    private $wallet_api_service;
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletUserApiService
     */
    private $wallet_user_api_service;

    /**
     * @param  \App\Models\Wallets\Databases\Services\WalletApiService  $WalletApiService
     */
    public function __construct(
        WalletApiService $WalletApiService,
        WalletUserApiService $WalletUserApiService

    ) {
        $this->wallet_api_service = $WalletApiService;
        $this->wallet_user_api_service = $WalletUserApiService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 01:00
     */
    public function index(Request $request)
    {
        $requester = (new WalletUserIndexRequest($request));

        $Validate = (new WalletUserIndexValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
                'data'    => [],
            ]);
        }
        $Wallet = $this->wallet_api_service
            ->setRequest($requester->toArray())
            ->getWalletWithUserByCode();

        $response = [
            'status'  => true,
            'code'    => 200,
            'message' => null,
            'data'    => [
                'wallet' => [
                    'users' => $Wallet->wallet_users->map(function ($User) {
                        return [
                            'id'       => Arr::get($User, 'id'),
                            'name'     => Arr::get($User, 'name'),
                            'is_admin' => Arr::get($User, 'is_admin') ? true : false,
                        ];
                    }),
                ],
            ],
        ];
        return response()->json($response);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/7/4 下午 06:41
     */
    public function destroy(Request $request)
    {
        $Response = [
            'status'  => false,
            'code'    => 400,
            'message' => '系統有誤',
            'data'    => [],
        ];
        $requester = (new WalletUserDestroyRequest($request));

        $Validate = (new WalletUserDestroyValidator($requester))->validate();
        if ($Validate->fails() === true) {
            Arr::set($Response, 'message', $Validate->errors()->first());
            return response()->json($Response);
        }
        $WalletUsers = $this->wallet_user_api_service
            ->setRequest($requester->toArray())
            ->getUserWithDetail();

        # 驗證
        if (is_null($WalletUsers) === false && $WalletUsers->created_wallet_details->isEmpty() === false) {
            Arr::set($Response, 'message', "成員已新增細項,無法刪除");
            return response()->json($Response);
        }
        try {
            $this->wallet_user_api_service
                ->setRequest($requester->toArray())
                ->delete();
            Arr::set($Response, 'message', null);
            Arr::set($Response, 'status', true);
            Arr::set($Response, 'code', 200);
        } catch (\Exception $e) {
            Arr::set($Response, 'message', $e);
        }
        return response()->json($Response);
    }
}
