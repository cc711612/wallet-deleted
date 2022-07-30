<?php

use Illuminate\Support\Str;

return [
    'api'            => [
        'member_token'        => env('MEMBER_CACHE_KEY', 'member_token.%s'),
        'wallet_member_token' => env('WALLET_MEMBER_CACHE_KEY', 'wallet_member_token.%s'),
    ],
    'wallets'        => '',
    'wallet_details' => 'wallet.details.%s',
    'wallet_user'    => 'wallet_user.%s',
];
