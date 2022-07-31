<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:53
 */

namespace App\Http\Controllers\Apis\Auth;

use Illuminate\Http\Request;
use App\Traits\AuthLoginTrait;
use App\Http\Requesters\Apis\Auth\LoginRequest;
use App\Http\Validators\Apis\Auth\LoginValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Http\Controllers\ApiController;
use App\Http\Resources\AuthResource;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Apis\Auth
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:54
 */
class LoginController extends ApiController
{
    use AuthLoginTrait;

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/20 下午 03:16
     */
    public function login(Request $request)
    {
        $Requester = (new LoginRequest($request));

        $Validate = (new LoginValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }
        $credentials = request(['account', 'password']);

        #認證失敗
        if (!Auth::attempt($credentials)) {
            return $this->response()->errorBadRequest("密碼有誤");
        }

        # set cache
        $this->MemberTokenCache();

        return $this->response()->success(
            (new AuthResource(Auth::user()))
                ->login()
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/28 上午 05:44
     */
    public function cache(Request $request)
    {
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => null,
            'data'    => [],
        ]);
    }
}
