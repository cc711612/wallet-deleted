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
                            'select_all'               => is_null(Arr::get($Detail, 'select_all')) ? false : true,
                            'value'                    => Arr::get($Detail, 'value', 0),
                            'created_user_id'          => Arr::get($Detail, 'created_by'),
                            'updated_user_id'          => Arr::get($Detail, 'updated_by'),
                            'created_at'               => Arr::get($Detail, 'created_at')->toDateTimeString(),
                            'updated_at'               => Arr::get($Detail, 'updated_at')->toDateTimeString(),
                        ];
                    })->toArray(),
                    'total'   => [
                        'revenue'     => $WalletDetailGroupByType->get(WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE,
                            collect([]))->groupBy('symbol_operation_type_id')->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT,
                            collect([]))->sum('value'),
                        'expenditure' => $WalletDetailGroupByType->get(WalletDetailTypes::WALLET_DETAIL_TYPE_GENERAL_EXPENSE,
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
                'message' => $Validate->errors(),
                'data'    => [],
            ]);
        }
        #Create
        $Entity = $this->wallet_api_service
            ->create(Arr::get($requester, 'wallets'));

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'wallet' => $Entity->toArray(),
            ],
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/20 下午 10:29
     */
    public function update(Request $request)
    {
        $requester = (new WalletDetailUpdateRequest($request));

        $Validate = (new WalletDetailUpdateValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
                'data'    => [],
            ]);
        }

        $Entity = $this->wallet_api_service
            ->update(Arr::get($requester, 'wallets.id'), Arr::get($requester, 'wallets'));

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [],
        ]);
    }
}
