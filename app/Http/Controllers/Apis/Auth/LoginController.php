<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:53
 */

namespace App\Http\Controllers\Apis\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Traits\AuthLoginTrait;
use App\Http\Requesters\Apis\Auth\LoginRequest;
use App\Http\Validators\Apis\Auth\LoginValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Apis\Auth
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:54
 */
class LoginController extends Controller
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
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
            ]);
        }
        $credentials = request(['account', 'password']);

        #認證失敗
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => "密碼有誤",
            ]);
        }
        # set cache
        $this->MemberTokenCache();
        # 最後更新的Wallet
        $Wallet = Auth::user()->wallets()->get()->sortByDesc('updated_at')->first();
        if (is_null($Wallet)) {
            $Wallet = collect([]);
        }
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'id'           => Arr::get(Auth::user(), 'id'),
                'name'         => Arr::get(Auth::user(), 'name'),
                'member_token' => Arr::get(Auth::user(), 'token'),
                'wallets'      => [
                    'id'   => Arr::get($Wallet, 'id'),
                    'code' => Arr::get($Wallet, 'code'),
                ],
            ],
        ]);
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
            'message' => [],
            'data'    => [],
        ]);
    }
}
