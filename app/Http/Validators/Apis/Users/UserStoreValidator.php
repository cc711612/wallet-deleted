<?php

namespace App\Http\Validators\Apis\Users;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class UserStoreValidator
 *
 * @package App\Http\Validators\Api\Users
 * @Author: Roy
 * @DateTime: 2021/7/30 下午 01:15
 */
class UserStoreValidator extends ValidatorAbstracts
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
            'name' => [
                'required',
            ],
            'password' => [
                'required',
                'min:6',
                'max:18'
            ],
            'email' => [
                'required',
                'unique:users,email',
                'email'
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
            'name.required' => 'name 為必填',
            'password.required' => 'password 為必填',
            'password.max' => 'password 至多18字元',
            'password.min' => 'password 至多6字元',
            'email.required' => 'email 為必填',
            'email.unique' => 'email 已存在',
            'email.email' => 'email 格式有誤'
        ];
    }
}
