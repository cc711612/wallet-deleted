<?php

namespace App\Http\Validators\Apis\Wallets\Details;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class WalletDetailIndexValidator
 *
 * @package App\Http\Validators\Apis\Wallets\Details
 * @Author: Roy
 * @DateTime: 2022/6/21 上午 12:21
 */
class WalletDetailIndexValidator extends ValidatorAbstracts
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
