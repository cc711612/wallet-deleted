<?php

namespace App\Http\Validators\Apis\Auth;

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
            'password' => [
                'required',
                'min:6',
                'max:18',
            ],
            'account'  => [
                'required',
                'exists:users,account',
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
            'password.required' => 'password 為必填',
            'password.max'      => 'password 至多18字元',
            'password.min'      => 'password 至少6字元',
            'account.required'  => 'account 為必填',
            'account.exists'    => 'account not exist',
        ];
    }
}
