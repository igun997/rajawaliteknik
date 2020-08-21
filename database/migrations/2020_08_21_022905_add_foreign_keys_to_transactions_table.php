<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('ref_id', 'transactions_ibfk_1')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('ref_id', 'transactions_ibfk_2')->references('id')->on('purchases')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id', 'transactions_ibfk_3')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_ibfk_1');
            $table->dropForeign('transactions_ibfk_2');
            $table->dropForeign('transactions_ibfk_3');
        });
    }
}
