<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class WalletUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * @param $request
     *
     * @Author: Roy
     * @DateTime: 2022/7/31 下午 01:49
     */
    public function index()
    {
        return [
            'wallet' => [
                'users' => $this->resource->wallet_users->map(function ($User) {
                    return [
                        'id'       => Arr::get($User, 'id'),
                        'name'     => Arr::get($User, 'name'),
                        'is_admin' => Arr::get($User, 'is_admin') ? true : false,
                    ];
                }),
            ],
        ];
    }
}
