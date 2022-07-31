<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function login()
    {
        $Wallet =$this->resource->wallets()->get()->sortByDesc('updated_at')->first();
        if (is_null($Wallet)) {
            $Wallet = collect([]);
        }
        return [
            'id'           => Arr::get($this->resource, 'id'),
            'name'         => Arr::get($this->resource, 'name'),
            'member_token' => Arr::get($this->resource, 'token'),
            'wallet'       => [
                'id'   => Arr::get($Wallet, 'id'),
                'code' => Arr::get($Wallet, 'code'),
            ],
        ];
    }
}
