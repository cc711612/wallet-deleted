<?php

namespace App\Http\Validators\Apis\Wallets\Details;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;

/**
 * Class WalletDetailUpdateValidator
 *
 * @package App\Http\Validators\Apis\Wallets\Details
 * @Author: Roy
 * @DateTime: 2022/6/21 上午 12:17
 */
class WalletDetailUpdateValidator extends ValidatorAbstracts
{
    /**
     * @var \App\Concerns\Databases\Contracts\Request
     */
    protected $request;

    /**
     * @param  \App\Concerns\Databases\Contracts\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \string[][]
     * @Author: Roy
     * @DateTime: 2022/6/20 下午 10:35
     */
    protected function rules(): array
    {
        return [
            'wallets.id'                              => [
                'required',
                'exists:wallets,id',
            ],
            'wallet_users.id'                         => [
                'required',
                'exists:wallet_users,id',
            ],
            'wallet_details.type'                     => [
                'required',
                Rule::in([
                    WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE,
                    WalletDetailTypes::WALLET_DETAIL_TYPE_GENERAL_EXPENSE,
                ]),
            ],
            'wallet_details.symbol_operation_type_id' => [
                'required',
                Rule::in([
                    SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT,
                    SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT,
                ]),
            ],
            'wallet_details.title'                    => [
                'required',
            ],
            'wallet_details.value'                    => [
                'required',
                'integer',
                'min:1',
            ],
            'wallet_details.select_all'               => [
                'required',
                Rule::in([
                    0,
                    1,
                ]),
            ],
            'wallet_details.created_by'               => [
                'required',
                'integer',
            ],
        ];
    }

    /**
     * @return string[]
     * @Author: Roy
     * @DateTime: 2022/6/20 下午 10:35
     */
    protected function messages(): array
    {
        return [
            'wallets.id.required'                              => '帳本錯誤，請重新整理',
            'wallets.id.exists'                                => '帳本錯誤，請重新整理',
            'wallet_users.id.required'                         => '非帳本內成員，請重新整理',
            'wallet_users.id.exists'                           => '非帳本內成員，請重新整理',
            'wallet_details.type.required'                     => '帳款類別錯誤，請重新整理',
            'wallet_details.type.in'                           => '帳款類別錯誤，請重新整理',
            'wallet_details.symbol_operation_type_id.required' => '收入支出類別錯誤，請重新整理',
            'wallet_details.symbol_operation_type_id.in'       => '收入支出類別錯誤，請重新整理',
            'wallet_details.title.required'                    => '標題 為必填',
            'wallet_details.value.required'                    => '金額 為必填',
            'wallet_details.value.integer'                     => '金額 為正整數',
            'wallet_details.value.min'                         => '金額 最小為1',
            'wallet_details.select_all.required'               => '系統錯誤，請重新整理',
            'wallet_details.select_all.in'                     => '系統錯誤，請重新整理',
            'wallet_details.created_by.required'               => '系統錯誤，請重新整理',
            'wallet_details.created_by.integer'                => '系統錯誤，請重新整理',
        ];
    }

}
