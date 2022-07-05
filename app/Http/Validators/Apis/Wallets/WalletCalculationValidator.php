<?php

namespace App\Http\Validators\Apis\Wallets;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class WalletCalculationValidator
 *
 * @package App\Http\Validators\Apis\Wallets
 * @Author: Roy
 * @DateTime: 2022/7/5 上午 10:18
 */
class WalletCalculationValidator extends ValidatorAbstracts
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
            'wallets.id'      => [
                'required',
                'exists:wallets,id',
            ],
            'wallet_users.id' => [
                'required',
                Rule::exists('wallet_users', 'id')->where(function ($query) {
                    $query->where('wallet_id', Arr::get($this->request, 'wallets.id'));
                }),
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
            'wallets.id.required'      => '系統異常',
            'wallets.id.exists'        => '系統異常',
            'wallet_users.id.required' => '系統異常',
            'wallet_users.id.exists'   => '非帳本內成員',
        ];
    }
}
