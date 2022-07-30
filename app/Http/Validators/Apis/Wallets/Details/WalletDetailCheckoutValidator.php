<?php

namespace App\Http\Validators\Apis\Wallets\Details;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;

/**
 * Class WalletDetailCheckoutValidator
 *
 * @package App\Http\Validators\Apis\Wallets\Details
 * @Author: Roy
 * @DateTime: 2022/7/30 下午 06:58
 */
class WalletDetailCheckoutValidator extends ValidatorAbstracts
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
            'wallets.id'                 => [
                'required',
                Rule::exists('wallets', 'id')->where(function ($query) {
                    return $query->where('status', 1);
                }),
            ],
            'wallet_users.id'            => [
                'required',
                'exists:wallet_users,id',
            ],
            'wallet_details.checkout_by' => [
                'required',
                'integer',
            ],
            'checkout.id'                => [
                'array',
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
            'wallets.id.required'                => '帳本錯誤，請重新整理',
            'wallets.id.exists'                  => '帳本錯誤，請重新整理',
            'wallet_users.id.required'           => '非帳本內成員，請重新整理',
            'wallet_users.id.exists'             => '非帳本內成員，請重新整理',
            'wallet_details.id.required'         => '帳本錯誤，請重新整理',
            'wallet_details.id.exists'           => '帳本錯誤，請重新整理',
            'wallet_details.deleted_by.required' => '系統錯誤，請重新整理',
            'wallet_details.deleted_by.integer'  => '系統錯誤，請重新整理',
        ];
    }

}
