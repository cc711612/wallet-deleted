<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/20 下午 03:39
 */

namespace App\Http\Controllers\Apis\Wallets;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requesters\Apis\Wallets\WalletIndexRequest;
use App\Models\Wallets\Databases\Services\WalletApiService;
use Illuminate\Support\Arr;
use App\Traits\ApiPaginateTrait;
use App\Http\Requesters\Apis\Wallets\WalletStoreRequest;
use App\Http\Validators\Apis\Wallets\WalletStoreValidator;
use App\Http\Requesters\Apis\Wallets\WalletUpdateRequest;
use App\Http\Validators\Apis\Wallets\WalletUpdateValidator;
use Illuminate\Support\Carbon;
use App\Http\Requesters\Apis\Wallets\Details\WalletDetailUserRequest;
use App\Http\Validators\Apis\Wallets\Details\WalletDetailUserValidator;

class WalletController extends Controller
{
    use ApiPaginateTrait;

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

        $response = [
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'paginate' => $this->handleApiPageInfo($Wallets),
                'wallets'  => $Wallets->getCollection()->map(function ($wallet) {
                    return [
                        'id'         => Arr::get($wallet, 'id'),
                        'title'      => Arr::get($wallet, 'title'),
                        'code'       => Arr::get($wallet, 'code'),
                        'user'       => [
                            'id'   => Arr::get($wallet, 'users.id'),
                            'name' => Arr::get($wallet, 'users.name'),
                        ],
                        'updated_at' => Arr::get($wallet, 'updated_at')->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
        ];

        return response()->json($response);
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
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
                'data'    => [],
            ]);
        }
        #Create
        $Entity = $this->wallet_api_service
            ->setRequest($requester->toArray())
            ->createWalletWithUser();

        if (is_null($Entity) === false) {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => [],
                'data'    => [
                    'wallet' => [
                        'id'         => Arr::get($Entity, 'id'),
                        'code'       => Arr::get($Entity, 'code'),
                        'title'      => Arr::get($Entity, 'title'),
                        'status'     => Arr::get($Entity, 'status'),
                        'created_at' => Arr::get($Entity, 'created_at', Carbon::now())->toDateTimeString(),
                    ],
                ],
            ]);
        }
        return response()->json([
            'status'  => false,
            'code'    => 400,
            'message' => "系統發生錯誤",
            'data'    => [],
        ]);
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
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
                'data'    => [],
            ]);
        }

        $Entity = $this->wallet_api_service
            ->update(Arr::get($requester, 'wallets.id'), Arr::get($requester, 'wallets'));

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [],
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2022/6/21 上午 01:00
     */
    public function user(Request $request)
    {
        $requester = (new WalletDetailUserRequest($request));

        $Validate = (new WalletDetailUserValidator($requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors()->first(),
                'data'    => [],
            ]);
        }
        $Wallet = $this->wallet_api_service
            ->setRequest($requester->toArray())
            ->getWalletWithUserByCode();

        $response = [
            'status'  => true,
            'code'    => 200,
            'message' => null,
            'data'    => [
                'wallet' => [
                    'users' => $Wallet->wallet_users->map(function ($User) {
                        return [
                            'id'       => Arr::get($User, 'id'),
                            'name'     => Arr::get($User, 'name'),
                            'is_admin' => Arr::get($User, 'is_admin') ? true : false,
                        ];
                    }),
                ],
            ],
        ];
        return response()->json($response);
    }
}
