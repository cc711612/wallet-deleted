<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitializationWalletUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('流水號');
            $table->unsignedBigInteger('wallet_id')->unsigned();
            $table->unsignedBigInteger('user_id')->nullable()->comment('users id');
            $table->string('name')->nullable();
            $table->string('token')->nullable();
            $table->smallInteger('is_admin')->default(0)->comment('管理員 1 yes : 0 no');
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
        Schema::dropIfExists('wallet_users');
    }
}
