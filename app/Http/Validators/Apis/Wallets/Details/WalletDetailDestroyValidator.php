<?php

namespace App\Http\Validators\Apis\Wallets\Details;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;

/**
 * Class WalletDetailDestroyValidator
 *
 * @package App\Http\Validators\Apis\Wallets\Details
 * @Author: Roy
 * @DateTime: 2022/6/25 下午 05:53
 */
class WalletDetailDestroyValidator extends ValidatorAbstracts
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
            'wallets.id'                => [
                'required',
                'exists:wallets,id',
            ],
            'wallet_users.id'           => [
                'required',
                'exists:wallet_users,id',
            ],
            'wallet_details.id'         => [
                'required',
                'exists:wallet_details,id',
            ],
            'wallet_details.deleted_by' => [
                'required',
                'integer',
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
            'wallets.id.required'                => '帳本錯誤，請重新整理',
            'wallets.id.exists'                  => '帳本錯誤，請重新整理',
            'wallet_users.id.required'           => '非帳本內成員，請重新整理',
            'wallet_users.id.exists'             => '非帳本內成員，請重新整理',
            'wallet_details.id.required'         => '帳本錯誤，請重新整理',
            'wallet_details.id.exists'           => '帳本錯誤，請重新整理',
            'wallet_details.deleted_by.required' => '系統錯誤，請重新整理',
            'wallet_details.deleted_by.integer'  => '系統錯誤，請重新整理',
        ];
    }

}
