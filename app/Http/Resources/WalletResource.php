<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\ApiPaginateTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;

/**
 * Class WalletResource
 *
 * @package App\Http\Resources
 * @Author: Roy
 * @DateTime: 2022/7/31 下午 11:26
 */
class WalletResource extends JsonResource
{
    use ApiPaginateTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * @return array
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 10:42
     */
    public function index()
    {
        return [
            'paginate' => $this->handleApiPageInfo($this->resource),
            'wallets'  => $this->resource->getCollection()->map(function ($wallet) {
                return [
                    'id'         => Arr::get($wallet, 'id'),
                    'title'      => Arr::get($wallet, 'title'),
                    'code'       => Arr::get($wallet, 'code'),
                    'status'     => Arr::get($wallet, 'status'),
                    'user'       => [
                        'id'   => Arr::get($wallet, 'users.id'),
                        'name' => Arr::get($wallet, 'users.name'),
                    ],
                    'updated_at' => Arr::get($wallet, 'updated_at')->format('Y-m-d H:i:s'),
                    'created_at' => Arr::get($wallet, 'created_at')->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }

    /**
     * @return array[]
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 10:49
     */
    public function store()
    {
        return [
            'wallet' => [
                'id'         => Arr::get($this->resource, 'id'),
                'code'       => Arr::get($this->resource, 'code'),
                'title'      => Arr::get($this->resource, 'title'),
                'status'     => Arr::get($this->resource, 'status'),
                'created_at' => Arr::get($this->resource, 'created_at', Carbon::now())->toDateTimeString(),
            ],
        ];
    }

    /**
     * @return array[]
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 11:25
     */
    public function calculation()
    {
        $Wallet = $this->resource;
        $WalletDetailGroupByType = $Wallet->wallet_details->groupBy('type');
        $WalletDetailGroupBySymbolOperationType = $Wallet->wallet_details->groupBy('symbol_operation_type_id');
        # 公費
        $Public = $WalletDetailGroupByType->get(WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE, collect([]));
        # 一般花費
        $General = $WalletDetailGroupByType->get(WalletDetailTypes::WALLET_DETAIL_TYPE_GENERAL_EXPENSE, collect([]));
        # 帳本成員
        $WalletUsers = $Wallet->wallet_users;
        $ExpenseDetails = $General->groupBy('symbol_operation_type_id')->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT,
            collect([]))->toArray();
        $UserExpenseDetails = [];
        $WalletDetails = [];
        $UserPayments = [];

        foreach ($ExpenseDetails as $Detail) {
            $Users = Arr::get($Detail, 'wallet_users', []);
            # 預設值
            $WalletDetails[Arr::get($Detail, 'id')] = [
                'wallet_details_id' => Arr::get($Detail, 'id'),
                'total'             => [
                    'income'   => 0,
                    'expenses' => Arr::get($Detail, 'value'),
                ],
            ];
            # 代墊費
            if (is_null(Arr::get($Detail, 'payment_wallet_user_id')) === false) {
                $UserPayments[Arr::get($Detail, 'payment_wallet_user_id')] [] = [
                    'user_id'   => Arr::get($Detail, 'payment_wallet_user_id'),
                    'detail_id' => Arr::get($Detail, 'id'),
                    'value'     => Arr::get($Detail, 'value'),
                ];
            }
            # 分攤
            if (count($Users) != 0) {
                # 均價
                $average_expense_value = ceil(Arr::get($Detail, 'value', 0) / count($Users));
                foreach ($Users as $User) {
                    $UserExpenseDetails[$User['id']] [] = [
                        'user_id'   => Arr::get($User, 'id'),
                        'detail_id' => Arr::get($Detail, 'id'),
                        'value'     => $average_expense_value,
                    ];
                    $WalletDetails[Arr::get($Detail, 'id')]['total']['income'] += $average_expense_value;
                    $WalletDetails[Arr::get($Detail, 'id')]['users'][] = Arr::get($User, 'id');
                }
            }
        }
        return [
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
        ];
    }
}
