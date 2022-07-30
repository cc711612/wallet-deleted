<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldWalletDetailsCheckoutAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet_details', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('checkout_by')->nullable()->comment('創建ID')->after('value');
            $table->dateTime('checkout_at')->nullable()->comment('結帳時間')->after('deleted_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_details', function (Blueprint $table) {
            //
            $table->dropColumn('checkout_by');
            $table->dropColumn('checkout_at');
        });
    }
}
