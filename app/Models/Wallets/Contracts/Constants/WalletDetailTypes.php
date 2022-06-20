<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 04:01
 */

namespace App\Models\Wallets\Contracts\Constants;

interface WalletDetailTypes
{
    # 公費
    const WALLET_DETAIL_TYPE_PUBLIC_EXPENSE = 1;
    # 非公費
    const WALLET_DETAIL_TYPE_GENERAL_EXPENSE = 2;
}
