<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitializationUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('流水號');
            $table->string('name')->nullable();
            $table->string('account')->nullable()->unique();
            $table->text('image')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('password');
            $table->string('token');
            $table->timestamps();
            $table->softDeletes();
            // 建立索引鍵
            $table->index(['account', 'deleted_at'], "index-account-deleted_at");
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
        Schema::dropIfExists('users');
    }
}
