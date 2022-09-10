<?php
/**
 * @Author: Roy
 * @DateTime: 2022/9/10 下午 04:42
 */

namespace App\Http\Controllers\Apis\Logs;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\LineMessageTrait;

class FrontLogController extends ApiController
{
    use LineMessageTrait;

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy
     * @Author: Roy
     * @DateTime: 2022/9/10 下午 04:44
     */
    public function normal(Request $request)
    {
        if (is_null($request->get('message')) === false) {
            Log::channel('front')->info($request->get('message'));
        }
        return $this->response()->success();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy
     * @Author: Roy
     * @DateTime: 2022/9/10 下午 04:46
     */
    public function serious(Request $request)
    {
        if (is_null($request->get('message')) === false) {
            Log::channel('front')->critical($request->get('message'));
            $this->sendMessage($request->get('message'));
        }
        return $this->response()->success();
    }
}
