<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitializationWallets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('wallets', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('流水號');
            $table->unsignedBigInteger('user_id')->nullable()->comment('創建ID');
            $table->string('code')->nullable()->comment('房間編號');
            $table->string('title')->nullable();
            $table->smallInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // 建立索引鍵
            $table->index(['status', 'deleted_at'], "index-status-deleted_at");
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
        Schema::dropIfExists('wallets');
    }
}
