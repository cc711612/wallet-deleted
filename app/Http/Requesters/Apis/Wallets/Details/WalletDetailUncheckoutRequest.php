<?php

namespace App\Http\Requesters\Apis\Wallets\Details;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;
use Illuminate\Support\Carbon;

class WalletDetailUncheckoutRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'wallets.id'                 => null,
            'wallet_user'                => null,
            'wallet_users.id'            => null,
            'wallet_details.checkout_at' => null,
            'wallet_details.checkout_by' => null,
            'wallet_details.updated_by'  => null,
            'checkout_at'                => null,
        ];
    }

    /**
     * @param $row
     *
     * @return array
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function map($row): array
    {
        return [
            'wallets.id'                => Arr::get($row, 'wallet'),
            'wallet_users.id'           => Arr::get($row,
                sprintf("wallet_user.%s.id", Arr::get($row, 'wallet'))),
            'wallet_user'               => Arr::get($row,
                sprintf("wallet_user.%s", Arr::get($row, 'wallet'))),
            'wallet_details.updated_by' => Arr::get($row,
                sprintf("wallet_user.%s.id", Arr::get($row, 'wallet'))),
            'checkout_at'               => Arr::get($row, 'checkout_at'),
        ];
    }
}
