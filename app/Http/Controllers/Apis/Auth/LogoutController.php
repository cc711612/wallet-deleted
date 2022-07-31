<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:53
 */

namespace App\Http\Controllers\Apis\Auth;

use Illuminate\Http\Request;
use App\Traits\AuthLogoutTrait;
use Illuminate\Support\Arr;
use App\Http\Controllers\ApiController;

/**
 * Class LogoutController
 *
 * @package App\Http\Controllers\Apis\Auth
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:54
 */
class LogoutController extends ApiController
{
    use AuthLogoutTrait;

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/8/9 下午 04:12
     */
    public function logout(Request $request)
    {
        # clean cache
        $this->cleanToken(Arr::get($request, 'user.token'));

        return $this->response()->success();
    }
}
