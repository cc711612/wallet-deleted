<?php

namespace App\Http\Validators\Apis\Wallets\Users;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class WalletUserIndexValidator
 *
 * @package App\Http\Validators\Apis\Wallets\Users
 * @Author: Roy
 * @DateTime: 2022/7/4 下午 05:47
 */
class WalletUserIndexValidator extends ValidatorAbstracts
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
            'wallets.code' => [
                'required',
                Rule::exists('wallets', 'code'),
            ],
//            'wallets.id'   => [
//                'required',
//                Rule::exists('wallets', 'id')->where(function ($query) {
//                    $query->where('code', Arr::get($this->request, 'wallets.code'));
//                }),
//            ],
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
//            'wallets.id.required'   => '系統異常',
//            'wallets.id.exists'     => '帳本驗證碼錯誤',
            'wallets.code.required' => '帳本驗證碼為必填',
            'wallets.code.exists'   => '帳本驗證碼錯誤',
        ];
    }
}
