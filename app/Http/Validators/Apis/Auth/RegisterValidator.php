<?php

namespace App\Http\Validators\Apis\Auth;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class RegisterValidator
 *
 * @package App\Http\Validators\Apis\Auth
 * @Author: Roy
 * @DateTime: 2022/6/21 上午 11:15
 */
class RegisterValidator extends ValidatorAbstracts
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
            'password' => [
                'required',
                'min:6',
                'max:18',
            ],
            'account'  => [
                'required',
                'unique:users,account',
            ],
            'name'     => [
                'required',
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
            'password.required' => '密碼 為必填',
            'password.max'      => '密碼 至多18字元',
            'password.min'      => '密碼 至多6字元',
            'account.required'  => '帳號 為必填',
            'account.unique'    => '帳號已存在',
            'name.required'     => '暱稱 為必填',
        ];
    }
}
