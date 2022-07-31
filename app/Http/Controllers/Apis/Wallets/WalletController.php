<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 03:39
 */

namespace App\Http\Controllers\Apis\Wallets;

use Illuminate\Http\Request;
use App\Http\Requesters\Apis\Wallets\WalletIndexRequest;
use App\Models\Wallets\Databases\Services\WalletApiService;
use Illuminate\Support\Arr;
use App\Http\Requesters\Apis\Wallets\WalletStoreRequest;
use App\Http\Validators\Apis\Wallets\WalletStoreValidator;
use App\Http\Requesters\Apis\Wallets\WalletUpdateRequest;
use App\Http\Validators\Apis\Wallets\WalletUpdateValidator;
use App\Http\Requesters\Apis\Wallets\WalletCalculationRequest;
use App\Http\Validators\Apis\Wallets\WalletCalculationValidator;
use App\Http\Controllers\ApiController;
use App\Http\Resources\WalletResource;

class WalletController extends ApiController
{
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletApiService
     */
    private $wallet_api_service;

    /**
     * @param  \App\Models\Wallets\Databases\Services\WalletApiService  $WalletApiService
     */
    public function __construct(
        WalletApiService $WalletApiService
    ) {
        $this->wallet_api_service = $WalletApiService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 02:19
     */
    public function index(Request $request)
    {
        $requester = (new WalletIndexRequest($request));

        $Wallets = $this->wallet_api_service
            ->setRequest($requester->toArray())
            ->paginate();

        return $this->response()->success(
            (new WalletResource($Wallets))
                ->index()
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 02:19
     */
    public function store(Request $request)
    {
        $requester = (new WalletStoreRequest($request));

        $Validate = (new WalletStoreValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }
        try {
            $Wallet = $this->wallet_api_service
                ->setRequest($requester->toArray())
                ->createWalletWithUser();
        } catch (\Exception$exception) {
            return $this->response()->fail(json_encode($exception));
        }
        return $this->response()->success(
            (new WalletResource($Wallet))
                ->store()
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/6/20 下午 10:29
     */
    public function update(Request $request)
    {
        $requester = (new WalletUpdateRequest($request));

        $Validate = (new WalletUpdateValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }

        try {

            $this->wallet_api_service
                ->update(Arr::get($requester, 'wallets.id'), Arr::get($requester, 'wallets'));

        } catch (\Exception $exception) {
            return $this->response()->fail(json_encode($exception));
        }

        return $this->response()->success();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/7/5 上午 10:15
     */
    public function calculation(Request $request)
    {
        $requester = (new WalletCalculationRequest($request));

        $Validate = (new WalletCalculationValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
                'data'    => [],
            ]);
        }

        # 帳本
        $Wallet = $this->wallet_api_service
            ->setRequest($requester->toArray())
            ->getWalletUsersAndDetails();

        return $this->response()->success(
            (new WalletResource($Wallet))
                ->calculation()
        );
    }
}
