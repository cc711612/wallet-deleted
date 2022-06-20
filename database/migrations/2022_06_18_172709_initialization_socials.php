<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitializationSocials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('socials', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('流水號');
            $table->string('name')->nullable()->comment('第三方姓名');
            $table->string('email')->nullable()->comment('第三方Email');
            $table->unsignedSmallInteger('social_type')->comment('第三方類別');
            $table->string('social_type_value')->comment('第三方ID');
            $table->string('image', 2048)->nullable()->comment('第三方照片');
            $table->string('token',2048)->nullable()->comment('第三方token');
            $table->timestamps();
            $table->softDeletes();
            // 建立索引鍵
            $table->index(['deleted_at'], "index-deleted_at");
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
        Schema::dropIfExists('socials');
    }
}
