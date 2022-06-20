<?php

namespace App\Http\Validators\Apis\Wallets;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class WalletUpdateValidator
 *
 * @package App\Http\Validators\Apis\Wallets
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 10:30
 */
class WalletUpdateValidator extends ValidatorAbstracts
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
            'wallets.user_id' => [
                'required',
                'exists:users,id',
            ],
            'wallets.title'   => [
                'required',
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
            'wallets.id.exists'        => '系統異常',
            'wallets.id.required'      => '系統異常',
            'wallets.user_id.required' => '系統異常',
            'wallets.user_id.exists'   => '系統異常',
            'wallets.title.required'   => '帳簿名稱為必填',

        ];
    }
}
