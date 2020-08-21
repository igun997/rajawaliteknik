<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCashbonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_cashbons', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_id')->index('order_id');
            $table->double('total');
            $table->integer('user_id')->index('user_id');
            $table->date('created_at');
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_cashbons');
    }
}
