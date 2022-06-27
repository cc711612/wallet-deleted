<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 03:39
 */

namespace App\Http\Controllers\Apis\Wallets;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailIndexRequest;
use App\Models\Wallets\Databases\Services\WalletApiService;
use Illuminate\Support\Arr;
use App\Traits\ApiPaginateTrait;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailStoreRequest;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailStoreValidator;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailUpdateRequest;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailUpdateValidator;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailIndexValidator;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;
use App\Models\Wallets\Databases\Services\WalletDetailApiService;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailShowRequest;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailDestroyValidator;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailDestroyRequest;

class WalletDetailController extends Controller
{
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletApiService
     */
    private $wallet_api_service;
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletDetailApiService
     */
    private $wallet_detail_api_service;

    public function __construct(
        WalletApiService $WalletApiService,
        WalletDetailApiService $WalletDetailApiService
    ) {
        $this->wallet_api_service = $WalletApiService;
        $this->wallet_detail_api_service = $WalletDetailApiService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 12:30
     */
    public function index(Request $request)
    {
        $requester = (new WalletDetailIndexRequest($request));

        $Validate = (new WalletDetailIndexValidator($requester))->validate();
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
            ->getWalletWithDetail();

        if (is_null($Wallet)) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => "系統錯誤，請重新整理",
                'data'    => [],
            ]);
        }
        $WalletDetails = $Wallet->wallet_details;
        $WalletDetailGroupByType = $WalletDetails->groupBy('type');
        $response = [
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'wallet' => [
                    'id'      => Arr::get($Wallet, 'id'),
                    'code'    => Arr::get($Wallet, 'code'),
                    'title'   => Arr::get($Wallet, 'title'),
                    'details' => $WalletDetails->map(function ($Detail) {
                        return [
                            'id'                       => Arr::get($Detail, 'id'),
                            'type'                     => Arr::get($Detail, 'type'),
                            'title'                    => Arr::get($Detail, 'title'),
                            'payment_user_id'          => Arr::get($Detail, 'payment_wallet_user_id'),
                            'symbol_operation_type_id' => Arr::get($Detail, 'symbol_operation_type_id'),
                            'select_all'               => Arr::get($Detail, 'select_all') ? true : false,
                            'value'                    => Arr::get($Detail, 'value', 0),
                            'users'                    => $Detail->wallet_users->pluck('id')->toArray(),
                            'created_by'               => Arr::get($Detail, 'created_by'),
                            'updated_by'               => Arr::get($Detail, 'updated_by'),
                            'created_at'               => Arr::get($Detail, 'created_at')->toDateTimeString(),
                            'updated_at'               => Arr::get($Detail, 'updated_at')->toDateTimeString(),
                        ];
                    })->toArray(),
                    'total'   => [
                        'income'   => $WalletDetailGroupByType->get(WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE,
                            collect([]))->groupBy('symbol_operation_type_id')->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT,
                            collect([]))->sum('value'),
                        'expenses' => $WalletDetailGroupByType->get(WalletDetailTypes::WALLET_DETAIL_TYPE_GENERAL_EXPENSE,
                            collect([]))->groupBy('symbol_operation_type_id')->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT,
                            collect([]))->sum('value'),
                    ],
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
     * @DateTime: 2022/6/20 下午 07:52
     */
    public function store(Request $request)
    {
        $requester = (new WalletDetailStoreRequest($request));

        $Validate = (new WalletDetailStoreValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
                'data'    => [],
            ]);
        }
        #Create
        $Entity = $this->wallet_api_service
            ->setRequest($requester->toArray())
            ->createWalletDetail();

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [],
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/22 上午 12:23
     */
    public function update(Request $request)
    {
        $requester = (new WalletDetailUpdateRequest($request));

        $Validate = (new WalletDetailUpdateValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
                'data'    => [],
            ]);
        }

        $Entity = $this->wallet_detail_api_service
            ->setRequest($requester->toArray())
            ->updateWalletDetail();

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [],
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2022/6/25 下午 05:18
     */
    public function show(Request $request)
    {
        $Response = [
            'status'  => false,
            'code'    => 403,
            'message' => '認證錯誤',
            'data'    => [],
        ];

        $requester = (new WalletDetailShowRequest($request));

        $Detail = $this->wallet_detail_api_service
            ->setRequest($requester->toArray())
            ->findDetail();

        # 認證
        if (is_null($Detail) === true) {
            Arr::set($Response, 'code', 400);
            Arr::set($Response, 'message', '參數有誤');
            return response()->json($Response);
        }

        $response = [
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'wallet' => [
                    'id'            => Arr::get($requester, 'wallets.id'),
                    'wallet_detail' => [
                        'id'                       => Arr::get($Detail, 'id'),
                        'type'                     => Arr::get($Detail, 'type'),
                        'payment_wallet_user_id'   => Arr::get($Detail, 'payment_wallet_user_id'),
                        'title'                    => Arr::get($Detail, 'title'),
                        'symbol_operation_type_id' => Arr::get($Detail, 'symbol_operation_type_id'),
                        'select_all'               => Arr::get($Detail, 'select_all'),
                        'value'                    => Arr::get($Detail, 'value'),
                        'created_by'               => Arr::get($Detail, 'created_by'),
                        'updated_by'               => Arr::get($Detail, 'updated_by'),
                        'updated_at'               => Arr::get($Detail, 'updated_at')->toDateTimeString(),
                        'users'                    => Arr::get($Detail, 'wallet_users',
                            collect([]))->pluck('id')->toArray(),
                    ],
                ],
            ],
        ];

        return response()->json($response);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2022/6/25 下午 05:51
     */
    public function destroy(Request $request)
    {
        $Response = [
            'status'  => false,
            'code'    => 403,
            'message' => '認證錯誤',
            'data'    => [],
        ];

        $requester = (new WalletDetailDestroyRequest($request));

        $Validate = (new WalletDetailDestroyValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
                'data'    => [],
            ]);
        }
        $Detail = $this->wallet_detail_api_service
            ->find(Arr::get($requester, 'wallet_details.id'));

        if (is_null($Detail) === true) {
            Arr::set($Response, 'code', 400);
            Arr::set($Response, 'message', '參數有誤');
            return response()->json($Response);
        }
        if ($Detail->created_by != Arr::get($requester, 'wallet_users.id') && Arr::get($requester,
                'wallet_user.is_admin') != 1) {
            return response()->json($Response);
        }
        # 刪除
        $Detail->update(Arr::get($requester, 'wallet_details'));

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [],
        ]);
    }
}
