<?php

namespace App\Http\Validators\Apis\Wallets\Auth;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class LoginTokenValidator
 *
 * @package App\Http\Validators\Apis\Wallets\Auth
 * @Author: Roy
 * @DateTime: 2022/6/28 上午 05:37
 */
class LoginTokenValidator extends ValidatorAbstracts
{
    /**
     * @var \App\Concerns\Databases\Contracts\Request
     */
    protected $request;

    /**
     * UserStoreValidator constructor.
     *
     * @param  \App\Concerns\Databases\Contracts\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 01:16
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \string[][]
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 01:59
     */
    protected function rules(): array
    {
        return [
            'wallets.code'       => [
                'required',
            ],
            'wallets.id'         => [
                'required',
                'exists:wallets,id',
            ],
            'wallet_users.token' => [
                'required',
                Rule::exists('wallet_users', 'token')->where(function ($query) {
                    $query->where('wallet_id', Arr::get($this->request, 'wallets.id'));
                }),
            ],
        ];
    }

    /**
     * @return string[]
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 01:59
     */
    protected function messages(): array
    {
        return [
            'wallets.code.required'       => '帳本token 為必填',
            'wallets.id.required'         => '系統有誤',
            'wallets.id.exists'           => 'wallets is empty',
            'wallet_users.token.required' => 'token為必填',
            'wallet_users.token.exists'   => '此token不存在',
        ];
    }
}
