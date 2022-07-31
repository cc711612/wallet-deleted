<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 02:53
 */

namespace App\Http\Controllers\Apis\Auth;

use Illuminate\Http\Request;
use App\Traits\AuthLoginTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Http\Requesters\Apis\Auth\RegisterRequest;
use App\Http\Validators\Apis\Auth\RegisterValidator;
use App\Models\Users\Databases\Services\UserApiService;
use App\Http\Controllers\ApiController;
use App\Http\Resources\AuthResource;

/**
 * Class RegisterController
 *
 * @package App\Http\Controllers\Apis\Auth
 * @Author: Roy
 * @DateTime: 2022/6/21 上午 11:11
 */
class RegisterController extends ApiController
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
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }

        $UserEntity = (new UserApiService())
            ->create(Arr::get($requester, 'users'));

        if (is_null($UserEntity)) {
            return $this->response()->fail('新增失敗');
        }

        $credentials = request(['account', 'password']);

        #認證失敗
        if (!Auth::attempt($credentials)) {
            return $this->response()->errorBadRequest("註冊登入失敗");
        }
        # set cache
        $this->MemberTokenCache();

        return $this->response()->success(
            (new AuthResource(Auth::user()))
                ->login()
        );
    }
}
