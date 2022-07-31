<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 03:39
 */

namespace App\Http\Controllers\Apis\Wallets;

use Illuminate\Http\Request;
use App\Models\Wallets\Databases\Services\WalletApiService;
use App\Http\Requesters\Apis\Wallets\Users\WalletUserIndexRequest;
use App\Http\Validators\Apis\Wallets\Users\WalletUserIndexValidator;
use App\Http\Requesters\Apis\Wallets\Users\WalletUserDestroyRequest;
use App\Http\Validators\Apis\Wallets\Users\WalletUserDestroyValidator;
use App\Models\Wallets\Databases\Services\WalletUserApiService;
use App\Http\Resources\WalletUserResource;
use App\Http\Controllers\ApiController;

class WalletUserController extends ApiController
{
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletApiService
     */
    private $wallet_api_service;
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletUserApiService
     */
    private $wallet_user_api_service;


    /**
     * @param  \App\Models\Wallets\Databases\Services\WalletApiService  $WalletApiService
     */
    public function __construct(
        WalletApiService $WalletApiService,
        WalletUserApiService $WalletUserApiService
    ) {
        $this->wallet_api_service = $WalletApiService;
        $this->wallet_user_api_service = $WalletUserApiService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy|void
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 02:03
     */
    public function index(Request $request)
    {
        $requester = (new WalletUserIndexRequest($request));

        $Validate = (new WalletUserIndexValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }

        $Wallet = $this->wallet_api_service
            ->setRequest($requester->toArray())
            ->getWalletWithUserByCode();

        return $this->response()->success((new WalletUserResource($Wallet))->index());
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy|void
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 02:03
     */
    public function destroy(Request $request)
    {
        $requester = (new WalletUserDestroyRequest($request));

        $Validate = (new WalletUserDestroyValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }
        $WalletUsers = $this->wallet_user_api_service
            ->setRequest($requester->toArray())
            ->getUserWithDetail();

        # 驗證
        if (is_null($WalletUsers) === false && $WalletUsers->created_wallet_details->isEmpty() === false) {
            return $this->response()->errorBadRequest("成員已新增細項,無法刪除");
        }
        try {
            $this->wallet_user_api_service
                ->setRequest($requester->toArray())
                ->delete();
        } catch (\Exception $e) {
            return $this->response()->fail($e);
        }

        return $this->response()->success();
    }
}
