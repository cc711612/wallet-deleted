<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitializationWalletDetailWalletUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_detail_wallet_user', function (Blueprint $table) {
            $table->unsignedBigInteger('wallet_user_id')->unsigned();
            $table->unsignedBigInteger('wallet_detail_id')->unsigned();
            // 建立索引鍵
            $table->unique(['wallet_user_id','wallet_detail_id'], 'index-wallet_user_id-wallet_detail_id');
            $table->foreign('wallet_user_id')->on('wallet_users')->references('id');
            $table->foreign('wallet_detail_id')->on('wallet_details')->references('id');
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
        Schema::dropIfExists('wallet_detail_wallet_user');
    }
}
