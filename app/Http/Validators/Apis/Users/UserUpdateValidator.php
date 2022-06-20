<?php

namespace App\Http\Validators\Apis\Users;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class UserUpdateValidator
 *
 * @package App\Http\Validators\Api\Users
 * @Author: Roy
 * @DateTime: 2021/7/30 下午 02:14
 */
class UserUpdateValidator extends ValidatorAbstracts
{
    /**
     * @var \App\Concerns\Databases\Contracts\Request
     */
    protected $request;

    /**
     * UserUpdateValidator constructor.
     *
     * @param  \App\Concerns\Databases\Contracts\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/8/1 下午 09:51
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
            'id'=>[
                'required',
                Rule::exists('users')->where(function ($query) {
                    return $query
                        ->where('id', Arr::get($this->request,'id'))
                        ->whereNull('deleted_at')
                        ;
                }),
            ],
            'name' => [
                'required',
            ],
//            'password' => [
//                'required',
//                'min:6',
//                'max:18'
//            ],
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
            'id.required' => 'id 為必填',
            'id.exists'   => 'id not exist',
            'name.required' => 'name 為必填',
//            'password.required' => 'password 為必填',
//            'password.max' => 'password 至多18字元',
//            'password.min' => 'password 至多6字元',
        ];
    }
}
