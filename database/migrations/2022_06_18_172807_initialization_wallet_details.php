<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitializationWalletDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_details', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('流水號');
            $table->unsignedBigInteger('wallet_id')->nullable()->comment('wallet_id');
            $table->smallInteger('type')->nullable();
            $table->unsignedBigInteger('payment_wallet_user_id')->nullable()->comment('付款人');
            $table->string('title');
            $table->smallInteger('symbol_operation_type_id')->nullable()->comment('加減項');
            $table->smallInteger('select_all')->nullable()->comment('帳本內成員全選');
            $table->float('value')->default(0)->comment('付款金額');
            $table->unsignedBigInteger('created_by')->nullable()->comment('創建ID');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('更新ID');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('創建ID');
            $table->timestamps();
            $table->softDeletes();
            // 建立索引鍵
            $table->unique(['wallet_id', 'deleted_at'], 'index-wallet_id-deleted_at');
            $table->foreign('wallet_id')->on('wallets')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('wallet_details');
    }

}
