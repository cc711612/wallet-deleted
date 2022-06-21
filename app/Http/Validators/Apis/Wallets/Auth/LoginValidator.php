<?php

namespace App\Http\Validators\Apis\Wallets\Auth;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class LoginValidator
 *
 * @package App\Http\Validators\Api\Users
 * @Author: Roy
 * @DateTime: 2021/8/9 下午 04:16
 */
class LoginValidator extends ValidatorAbstracts
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
            'wallets.code'      => [
                'required',
            ],
            'wallets.id'        => [
                'required',
                'exists:wallets,id',
            ],
            'wallet_users.name' => [
                'required',
                Rule::exists('wallet_users', 'name')->where(function ($query) {
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
            'wallets.code.required'      => '帳本token 為必填',
            'wallets.id.required'        => '系統有誤',
            'wallets.id.exists'          => 'wallets is empty',
            'wallet_users.name.required' => '暱稱 為必填',
            'wallet_users.name.exists'   => '此用戶不存在',
        ];
    }
}
