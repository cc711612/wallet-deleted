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

class WalletController extends Controller
{
    use ApiPaginateTrait;

    private $wallet_api_service;

    public function __construct(
        WalletApiService $WalletApiService
    ) {
        $this->wallet_api_service = $WalletApiService;
    }

    /**
     * @param  \Illuminate\Http\Client\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2022/6/20 下午 04:43
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
     * @DateTime: 2022/6/20 下午 07:52
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
            ->create(Arr::get($requester, 'wallets'));

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'wallet' => $Entity->toArray(),
            ],
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
}
