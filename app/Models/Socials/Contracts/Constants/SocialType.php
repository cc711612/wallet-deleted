<?php

namespace App\Models\Socials\Contracts\Constants;

/**
 * Interface SocialType
 *
 * @package App\Models\Socials\Contracts\Constants
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 04:13
 */
interface SocialType
{
    const SOCIAL_TYPE_ACCOUNT      = 1;
    const SOCIAL_TYPE_EMAIL        = 2;
    const SOCIAL_TYPE_CELL_PHONE   = 3;
    const SOCIAL_TYPE_MAC_ADDRESS  = 4;
    const SOCIAL_TYPE_GOOGLE       = 5;
    const SOCIAL_TYPE_FACEBOOK     = 6;
    const SOCIAL_TYPE_LINKED_IN    = 7;
    const SOCIAL_TYPE_TWITTER      = 8;
    const SOCIAL_TYPE_LINE         = 9;
    const SOCIAL_TYPE_LINE_AT      = 10;
    const SOCIAL_TYPE_WECHAT       = 11;
    const SOCIAL_TYPE_YAHOO        = 12;
    const SOCIAL_TYPE_OTHER        = 13;
}
