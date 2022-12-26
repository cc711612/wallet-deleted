<?php
/**
 * @Author: Roy
 * @DateTime: 2022/12/26 下午 03:45
 */

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;

trait ResponseTrait
{
    public function getContentToArray(TestResponse $Response): array
    {
        return json_decode($Response->getContent(), 1);
    }
}
