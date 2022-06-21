<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:53
 */

namespace App\Http\Controllers\Apis\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Traits\AuthLoginTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Http\Requesters\Apis\Auth\RegisterRequest;
use App\Http\Validators\Apis\Auth\RegisterValidator;
use App\Models\Users\Databases\Services\UserApiService;

/**
 * Class RegisterController
 *
 * @package App\Http\Controllers\Apis\Auth
 * @Author: Roy
 * @DateTime: 2022/6/21 上午 11:11
 */
class RegisterController extends Controller
{
    use AuthLoginTrait;

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/20 下午 03:16
     */
    public function register(Request $request)
    {
        $requester = (new RegisterRequest($request));

        $Validate = (new RegisterValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
            ]);
        }
        $UserEntity = (new UserApiService())
//            ->setRequest($requester->toArray())
            ->create(Arr::get($requester, 'users'));

        if (is_null($UserEntity)) {
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

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'id'           => Arr::get(Auth::user(), 'id'),
                'name'         => Arr::get(Auth::user(), 'name'),
                'member_token' => Arr::get(Auth::user(), 'token'),
            ],
        ]);
    }
}
