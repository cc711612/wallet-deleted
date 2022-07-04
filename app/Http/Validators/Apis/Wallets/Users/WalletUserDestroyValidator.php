<?php

namespace App\Http\Validators\Apis\Wallets\Users;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;
use Illuminate\Support\Arr;

/**
 * Class WalletUserDestroyValidator
 *
 * @package App\Http\Validators\Apis\Wallets\Users
 * @Author: Roy
 * @DateTime: 2022/7/4 下午 05:48
 */
class WalletUserDestroyValidator extends ValidatorAbstracts
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
            'wallets.id'              => [
                'required',
                'exists:wallets,id',
            ],
            'wallet_users.deleted_by' => [
                'required',
                Rule::exists('wallet_users', 'id')->where(function ($query) {
                    return $query
                        ->where('wallet_id', Arr::get($this->request, 'wallets.id'))
                        ->where('is_admin', 1);
                }),
            ],
            'wallet_users.id'         => [
                'required',
                Rule::exists('wallet_users', 'id')->where(function ($query) {
                    return $query
                        ->where('wallet_id', Arr::get($this->request, 'wallets.id'))
                        ->where('is_admin', 0);
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
            'wallets.id.required'              => '帳本錯誤，請重新整理',
            'wallets.id.exists'                => '帳本錯誤，請重新整理',
            'wallet_users.deleted_by.required' => '非帳本內成員，請重新整理',
            'wallet_users.deleted_by.exists'   => '非帳本管理員',
            'wallet_users.id.required'         => '非帳本內成員，請重新整理',
            'wallet_users.id.exists'           => '非帳本內成員，請重新整理',
        ];
    }
}
