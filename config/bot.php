<?php

return [
    'line' => [
        'access_token'   => env('LINE_BOT_TOKEN'),
        'channel_secret' => env('LINE_BOT_SECRET'),
        'admin_user_ids'  => env('LINE_ADMIN_USERID', ['U1d40789aa8461e74ead62181b1abc442','Uc4102f98704f54c9cecd8448f4a462e5']),
    ],
];
