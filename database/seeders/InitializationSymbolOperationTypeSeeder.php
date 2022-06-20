<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SymbolOperationTypes\Contracts\Constants\SymbolOperationTypes;
use Illuminate\Support\Facades\Schema;
use App\Models\SymbolOperationTypes\Databases\Entities\SymbolOperationTypeEntity;

class InitializationSymbolOperationTypeSeeder extends Seeder
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
                'id' => SymbolOperationTypes::SYMBOL_OPERATION_TYPE_INCREMENT,
                'title' => '加項',
            ],
            [
                'id' => SymbolOperationTypes::SYMBOL_OPERATION_TYPE_DECREMENT,
                'title' => '減項',
            ]
        ];

        Schema::disableForeignKeyConstraints();
        SymbolOperationTypeEntity::truncate();
        SymbolOperationTypeEntity::insert($insert_data);
        Schema::enableForeignKeyConstraints();

        echo self::class . ' Complete' . PHP_EOL . PHP_EOL;

    }
}
