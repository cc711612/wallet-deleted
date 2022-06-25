<?php

namespace App\Http\Requesters\Apis\Wallets\Details;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;

class WalletDetailShowRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'wallets.id'               => null,
            'wallet_users.id'          => null,
            'wallet_details.id'        => null,
            'wallet_details.wallet_id' => null,
            'wallet_user'              => null,
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
            'wallets.id'               => Arr::get($row, 'wallet'),
            'wallet_users.id'          => Arr::get($row,
                sprintf("wallet_user.%s.id", Arr::get($row, 'wallet'))),
            'wallet_details.id'        => Arr::get($row, 'detail'),
            'wallet_details.wallet_id' => Arr::get($row, 'wallet'),
            'wallet_user'              => Arr::get($row,
                sprintf("wallet_user.%s", Arr::get($row, 'wallet'))),
        ];
    }
}
