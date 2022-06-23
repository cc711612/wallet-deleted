<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Users\Databases\Entities\UserEntity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Wallets\Databases\Entities\WalletEntity;
use Illuminate\Support\Arr;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;
use App\Models\Wallets\Databases\Entities\WalletDetailEntity;

class InitializationUserWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert_data = [
            [
                'name'    => '冠融',
                'account' => 'cc711612',
            ],
            [
                'name'    => '友菘',
                'account' => 'seanttc',
            ],
        ];

        Schema::disableForeignKeyConstraints();
        # 清空
        UserEntity::truncate();
        WalletEntity::truncate();
        WalletDetailEntity::truncate();
        # create
        foreach ($insert_data as $data) {
            $token = Str::random(10);
            $UserEntity = UserEntity::create(
                array_merge($data, [
                    'password'    => '123456789',
                    'token'       => Str::random(10),
                    'verified_at' => Carbon::now()->toDateTimeString(),
                ])
            );
            $WalletEntity = $UserEntity->wallets()->create([
                'title'  => '測試用帳本_'.mt_rand(1, 10),
                'code'   => Str::random(6),
                'status' => 1,
            ]);
            $WalletUserEntity = $WalletEntity->wallet_users()->updateOrCreate([
                'name' => Arr::get($data, 'name'),
            ], [
                'user_id'  => $UserEntity->id,
                'name'     => Arr::get($data, 'name'),
                'token'    => $token,
                'is_admin' => 1,
            ]);
            # 公費
            $WalletEntity->wallet_details()->create([
                'type'                     => WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE,
                'title'                    => '公費',
                'symbol_operation_type_id' => SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT,
                'value'                    => 8000,
                'created_by'               => $WalletUserEntity->id,
                'updated_by'               => $WalletUserEntity->id,

            ]);
            # 餐費
            $WalletDetailEntity = $WalletEntity->wallet_details()->create([
                'type'                     => WalletDetailTypes::WALLET_DETAIL_TYPE_GENERAL_EXPENSE,
                'title'                    => '餐費',
                'symbol_operation_type_id' => SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT,
                'value'                    => 1000,
                'created_by'               => $WalletUserEntity->id,
                'updated_by'               => $WalletUserEntity->id,
            ]);
            $WalletDetailEntity->wallet_users()->sync([$WalletUserEntity->id]);
            unset($WalletDetailEntity);
            unset($OrderEntity);
            unset($WalletEntity);
        }
        Schema::enableForeignKeyConstraints();

        echo self::class.' Complete'.PHP_EOL.PHP_EOL;

    }
}
