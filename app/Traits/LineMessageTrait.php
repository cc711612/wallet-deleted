<?php

namespace App\Traits;

use App\Jobs\SendLineMessage;


/**
 * Trait ScheduleTrait
 *
 * @package App\Traits
 * @Author: Roy
 * @DateTime: 2022/4/23 上午 11:29
 */
trait LineMessageTrait
{
    /**
     * @param  string  $message
     *
     * @return bool
     * @Author: Roy
     * @DateTime: 2022/4/23 上午 11:29
     */
    private function sendMessage(string $message, $user_id = null)
    {
        SendLineMessage::dispatch(
            [
                'message' => $message,
                'user_id' => $user_id,
            ]
        );
        return true;
    }
}
