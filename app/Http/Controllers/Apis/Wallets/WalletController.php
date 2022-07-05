<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 03:39
 */

namespace App\Http\Controllers\Apis\Wallets;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requesters\Apis\Wallets\WalletIndexRequest;
use App\Models\Wallets\Databases\Services\WalletApiService;
use Illuminate\Support\Arr;
use App\Traits\ApiPaginateTrait;
use App\Http\Requesters\Apis\Wallets\WalletStoreRequest;
use App\Http\Validators\Apis\Wallets\WalletStoreValidator;
use App\Http\Requesters\Apis\Wallets\WalletUpdateRequest;
use App\Http\Validators\Apis\Wallets\WalletUpdateValidator;
use Illuminate\Support\Carbon;
use App\Http\Requesters\Apis\Wallets\WalletCalculationRequest;
use App\Http\Validators\Apis\Wallets\WalletCalculationValidator;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;

class WalletController extends Controller
{
    use ApiPaginateTrait;

    /**
     * @var \App\Models\Wallets\Databases\Services\WalletApiService
     */
    private $wallet_api_service;

    /**
     * @param  \App\Models\Wallets\Databases\Services\WalletApiService  $WalletApiService
     */
    public function __construct(
        WalletApiService $WalletApiService
    ) {
        $this->wallet_api_service = $WalletApiService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 02:19
     */
    public function index(Request $request)
    {
        $requester = (new WalletIndexRequest($request));

        $Wallets = $this->wallet_api_service
            ->setRequest($requester->toArray())
            ->paginate();

        $response = [
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'paginate' => $this->handleApiPageInfo($Wallets),
                'wallets'  => $Wallets->getCollection()->map(function ($wallet) {
                    return [
                        'id'         => Arr::get($wallet, 'id'),
                        'title'      => Arr::get($wallet, 'title'),
                        'code'       => Arr::get($wallet, 'code'),
                        'user'       => [
                            'id'   => Arr::get($wallet, 'users.id'),
                            'name' => Arr::get($wallet, 'users.name'),
                        ],
                        'updated_at' => Arr::get($wallet, 'updated_at')->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
        ];

        return response()->json($response);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 02:19
     */
    public function store(Request $request)
    {
        $requester = (new WalletStoreRequest($request));

        $Validate = (new WalletStoreValidator($requester))->validate();
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
            ->createWalletWithUser();

        if (is_null($Entity) === false) {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => [],
                'data'    => [
                    'wallet' => [
                        'id'         => Arr::get($Entity, 'id'),
                        'code'       => Arr::get($Entity, 'code'),
                        'title'      => Arr::get($Entity, 'title'),
                        'status'     => Arr::get($Entity, 'status'),
                        'created_at' => Arr::get($Entity, 'created_at', Carbon::now())->toDateTimeString(),
                    ],
                ],
            ]);
        }
        return response()->json([
            'status'  => false,
            'code'    => 400,
            'message' => "系統發生錯誤",
            'data'    => [],
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
        $requester = (new WalletUpdateRequest($request));

        $Validate = (new WalletUpdateValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
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

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/7/5 上午 10:15
     */
    public function calculation(Request $request)
    {
        $requester = (new WalletCalculationRequest($request));

        $Validate = (new WalletCalculationValidator($requester))->validate();
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
            ->getWalletUsersAndDetails();
        $WalletDetailGroupByType = $Wallet->wallet_details->groupBy('type');
        $WalletDetailGroupBySymbolOperationType = $Wallet->wallet_details->groupBy('symbol_operation_type_id');
        # 公費
        $Public = $WalletDetailGroupByType->get(WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE, collect([]));
        # 一般花費
        $General = $WalletDetailGroupByType->get(WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE, collect([]));
        # 帳本成員
        $WalletUsers = $Wallet->wallet_users;
        $ExpenseDetails = $WalletDetailGroupBySymbolOperationType->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT,
            collect([]))->toArray();
        $UserExpenseDetails = [];
        $WalletDetails = [];
        $UserPayments = [];

        foreach ($ExpenseDetails as $Detail) {
            $Users = Arr::get($Detail, 'wallet_users', []);
            $WalletDetails[Arr::get($Detail, 'id')] = [
                'wallet_details_id' => Arr::get($Detail, 'id'),
                'total'             => [
                    'income'   => 0,
                    'expenses' => Arr::get($Detail, 'value'),
                ],
            ];

            if (count($Users) != 0) {
                $average_expense_value = ceil(Arr::get($Detail, 'value', 0) / count($Users));
                foreach ($Users as $User) {
                    $UserExpenseDetails[$User['id']] [] = [
                        'user_id'   => Arr::get($User, 'id'),
                        'detail_id' => Arr::get($Detail, 'id'),
                        'value'     => $average_expense_value,
                    ];

                    if (Arr::get($Detail, 'payment_wallet_user_id') == Arr::get($User, 'id')) {
                        $UserPayments[$User['id']] [] = [
                            'user_id'   => Arr::get($User, 'id'),
                            'detail_id' => Arr::get($Detail, 'id'),
                            'value'     => Arr::get($Detail, 'value'),
                        ];
                    }
                    $WalletDetails[Arr::get($Detail, 'id')]['total']['income'] += $average_expense_value;
                    $WalletDetails[Arr::get($Detail, 'id')]['users'][] = Arr::get($User, 'id');
                }
            }
        }
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'wallet' => [
                    'id'      => Arr::get($Wallet, 'id'),
                    'total'   => [
                        'public'   => [
                            'income'   => $Public->groupBy('symbol_operation_type_id')->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT,
                                collect([]))->sum('value'),
                            'expenses' => $Public->groupBy('symbol_operation_type_id')->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT,
                                collect([]))->sum('value'),
                        ],
                        'income'   => $WalletDetailGroupBySymbolOperationType->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT,
                            collect([]))->sum('value'),
                        'expenses' => $WalletDetailGroupBySymbolOperationType->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT,
                            collect([]))->sum('value'),
                    ],
                    'users'   => $WalletUsers->map(function ($userEntity) use ($UserExpenseDetails, $UserPayments) {
                        $UserExpenseDetail = collect(Arr::get($UserExpenseDetails, $userEntity->id, []));
                        $UserPayment = collect(Arr::get($UserPayments, $userEntity->id, []));
                        $expenses = $UserExpenseDetail->sum('value');
                        $income = $UserPayment->sum('value');
                        return [
                            'id'                        => Arr::get($userEntity, 'id'),
                            'name'                      => Arr::get($userEntity, 'name'),
                            'income'                    => $income,
                            'expenses'                  => $expenses,
                            'total'                     => $income - $expenses,
                            'wallet_details_id'         => $UserExpenseDetail->pluck('detail_id'),
                            'payment_wallet_details_id' => $UserPayment->pluck('detail_id'),
                        ];
                    }),
                    'details' => array_values($WalletDetails),
                ],
            ],
        ]);
    }

}
