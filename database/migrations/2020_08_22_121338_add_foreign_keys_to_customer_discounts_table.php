<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_discounts', function (Blueprint $table) {
            $table->foreign('customer_id', 'customer_discounts_ibfk_1')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('product_id', 'customer_discounts_ibfk_2')->references('id')->on('products')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_discounts', function (Blueprint $table) {
            $table->dropForeign('customer_discounts_ibfk_1');
            $table->dropForeign('customer_discounts_ibfk_2');
        });
    }
}
