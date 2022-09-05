<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 03:39
 */

namespace App\Http\Controllers\Apis\Wallets;

use Illuminate\Http\Request;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailIndexRequest;
use App\Models\Wallets\Databases\Services\WalletApiService;
use Illuminate\Support\Arr;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailStoreRequest;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailStoreValidator;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailUpdateRequest;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailUpdateValidator;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailIndexValidator;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use App\Models\Wallets\Databases\Services\WalletDetailApiService;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailShowRequest;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailDestroyValidator;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailDestroyRequest;
use App\Models\Wallets\Databases\Services\WalletUserApiService;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailCheckoutRequest;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailCheckoutValidator;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailUncheckoutRequest;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailUncheckoutValidator;
use App\Http\Controllers\ApiController;
use App\Http\Resources\WalletDetailResource;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;

class WalletDetailController extends ApiController
{
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletApiService
     */
    private $wallet_api_service;
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletDetailApiService
     */
    private $wallet_detail_api_service;
    /**
     * @var \App\Models\Wallets\Databases\Services\WalletUserApiService
     */
    private $wallet_user_api_service;

    /**
     * @param  \App\Models\Wallets\Databases\Services\WalletApiService  $WalletApiService
     * @param  \App\Models\Wallets\Databases\Services\WalletDetailApiService  $WalletDetailApiService
     * @param  \App\Models\Wallets\Databases\Services\WalletUserApiService  $WalletUserApiService
     */
    public function __construct(
        WalletApiService $WalletApiService,
        WalletDetailApiService $WalletDetailApiService,
        WalletUserApiService $WalletUserApiService
    ) {
        $this->wallet_api_service = $WalletApiService;
        $this->wallet_detail_api_service = $WalletDetailApiService;
        $this->wallet_user_api_service = $WalletUserApiService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy|void
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 11:54
     */
    public function index(Request $request)
    {
        $requester = (new WalletDetailIndexRequest($request));

        $Validate = (new WalletDetailIndexValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }

        $Wallet = $this->wallet_api_service
            ->setRequest($requester->toArray())
            ->getWalletWithDetail();

        if (is_null($Wallet)) {
            return $this->response()->fail("系統錯誤，請重新整理");
        }

        return $this->response()->success(
            (new WalletDetailResource($Wallet))
                ->index()
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy|void
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 11:54
     */
    public function store(Request $request)
    {
        $requester = (new WalletDetailStoreRequest($request));

        $Validate = (new WalletDetailStoreValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }

        if (Arr::get($requester, 'wallet_details.type') == WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE &&
            Arr::get($requester,
                'wallet_details.symbol_operation_type_id') == SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT
        ) {
            # 公費 & 減項 需檢查公費額度
            $Details = $this->wallet_detail_api_service
                ->getPublicDetailByWalletId(Arr::get($requester, 'wallets.id'));
            $DetailGroupBySymbol = $Details->groupBy('symbol_operation_type_id');
            $total = $DetailGroupBySymbol->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT)->sum('value') > $DetailGroupBySymbol->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT)->sum('value');
            if ($total - Arr::get($requester, 'wallet_details.value') < 0) {
                return $this->response()->errorBadRequest("公費結算金額不得為負數");
            }
        }

        if (Arr::get($requester, 'wallet_details.type') != WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE) {
            # 驗證users
            $ValidateWalletUsers = $this->wallet_user_api_service
                ->setRequest($requester->toArray())
                ->validateWalletUsers();

            if ($ValidateWalletUsers === false) {
                return $this->response()->errorBadRequest("分攤成員有誤");
            }
        }
        try {
            $this->wallet_api_service
                ->setRequest($requester->toArray())
                ->createWalletDetail();
        } catch (\Exception $exception) {
            return $this->response()->fail(json_encode($exception));
        }

        return $this->response()->success();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy|void
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 11:54
     */
    public function update(Request $request)
    {
        $requester = (new WalletDetailUpdateRequest($request));

        $Validate = (new WalletDetailUpdateValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }

        if (Arr::get($requester, 'wallet_details.type') == WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE &&
            Arr::get($requester,
                'wallet_details.symbol_operation_type_id') == SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT
        ) {
            # 公費 & 減項 需檢查公費額度
            $Details = $this->wallet_detail_api_service
                ->getPublicDetailByWalletId(Arr::get($requester, 'wallets.id'));
            $before_detail_value = 0;
            # 更新前為公帳細項
            $UpdateDetail = $Details->keyBy('id')->get(Arr::get($requester, 'wallet_details.id'));
            if (is_null($UpdateDetail) === false) {
                $before_detail_value = $UpdateDetail->value;
                if ($UpdateDetail->symbol_operation_type_id == SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT) {
                    $before_detail_value = 0 - $UpdateDetail->value;
                }
            }
            $DetailGroupBySymbol = $Details->groupBy('symbol_operation_type_id');
            $total = $DetailGroupBySymbol->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT)->sum('value') - $DetailGroupBySymbol->get(SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT)->sum('value');
            if ($total + $before_detail_value - Arr::get($requester, 'wallet_details.value') < 0) {
                return $this->response()->errorBadRequest("公費結算金額不得為負數");
            }
        }

        if (Arr::get($requester, 'wallet_details.type') != WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE) {
            # 驗證users
            $ValidateWalletUsers = $this->wallet_user_api_service
                ->setRequest($requester->toArray())
                ->validateWalletUsers();
            if ($ValidateWalletUsers === false) {
                return $this->response()->errorBadRequest("分攤成員有誤");
            }
        }
        try {
            $this->wallet_detail_api_service
                ->setRequest($requester->toArray())
                ->updateWalletDetail();
        } catch (\Exception $exception) {
            return $this->response()->fail(json_encode($exception));
        }
        return $this->response()->success();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy|void
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 11:54
     */
    public function show(Request $request)
    {
        $requester = (new WalletDetailShowRequest($request));

        $Detail = $this->wallet_detail_api_service
            ->setRequest($requester->toArray())
            ->findDetail();

        # 認證
        if (is_null($Detail) === true) {
            return $this->response()->errorBadRequest("參數有誤");
        }
        return $this->response()->success(
            (new WalletDetailResource($Detail))
                ->show($requester->toArray())
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy|void
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 11:54
     */
    public function destroy(Request $request)
    {
        $requester = (new WalletDetailDestroyRequest($request));

        $Validate = (new WalletDetailDestroyValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }
        $Detail = $this->wallet_detail_api_service
            ->find(Arr::get($requester, 'wallet_details.id'));

        if (is_null($Detail) === true) {
            return $this->response()->errorBadRequest("參數有誤");
        }
        if ($Detail->created_by != Arr::get($requester, 'wallet_users.id') && Arr::get($requester,
                'wallet_user.is_admin') != 1) {
            return $this->response()->errorUnauthorized("非admin");
        }
        try {
            # 刪除
            $Detail->update(Arr::get($requester, 'wallet_details'));
        } catch (\Exception $exception) {
            return $this->response()->fail(json_encode($exception));
        }
        return $this->response()->success();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy|void
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 11:56
     */
    public function checkout(Request $request)
    {
        $requester = (new WalletDetailCheckoutRequest($request));

        $Validate = (new WalletDetailCheckoutValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }
        try {

            $this->wallet_detail_api_service
                ->setRequest($requester->toArray())
                ->checkoutWalletDetails();

        } catch (\Exception $exception) {
            return $this->response()->fail(json_encode($exception));
        }

        return $this->response()->success();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Support\HigherOrderTapProxy|void
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 11:56
     */
    public function unCheckout(Request $request)
    {
        $requester = (new WalletDetailUncheckoutRequest($request));

        $Validate = (new WalletDetailUncheckoutValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return $this->response()->errorBadRequest($Validate->errors()->first());
        }

        try {

            $this->wallet_detail_api_service
                ->setRequest($requester->toArray())
                ->unCheckoutWalletDetails();
        } catch (\Exception $exception) {
            return $this->response()->fail(json_encode($exception));
        }

        return $this->response()->success();
    }
}
