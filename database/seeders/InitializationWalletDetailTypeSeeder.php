<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wallets\Contracts\Constants\WalletDetailTypes;
use Illuminate\Support\Facades\Schema;
use App\Models\Wallets\Databases\Entities\WalletDetailTypeEntity;

class InitializationWalletDetailTypeSeeder extends Seeder
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
                'id' => WalletDetailTypes::WALLET_DETAIL_TYPE_PUBLIC_EXPENSE,
                'title' => '公費',
            ]
        ];

        Schema::disableForeignKeyConstraints();
        WalletDetailTypeEntity::truncate();
        WalletDetailTypeEntity::insert($insert_data);
        Schema::enableForeignKeyConstraints();

        echo self::class . ' Complete' . PHP_EOL . PHP_EOL;

    }
}
