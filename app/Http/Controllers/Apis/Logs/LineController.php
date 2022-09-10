<?php
/**
 * @Author: Roy
 * @DateTime: 2022/9/10 下午 04:14
 */

namespace App\Http\Controllers\Apis\Logs;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LineController extends ApiController
{

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy
     * @Author: Roy
     * @DateTime: 2022/9/10 下午 04:16
     */
    public function store(Request $request)
    {
        Log::channel('bot')->info($request->toArray());
        return $this->response()->success();
    }
}
