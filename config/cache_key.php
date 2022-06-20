<?php

use Illuminate\Support\Str;

return [
    'api' => [
        'member_token' => env('MEMBER_CACHE_KEY', 'member_token.%s'),
    ],
];
