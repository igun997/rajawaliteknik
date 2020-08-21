<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('invoice_number', 100)->unique('invoice_number');
            $table->integer('status');
            $table->string('proof_docs', 100)->nullable();
            $table->integer('customer_id')->index('customer_id');
            $table->integer('user_id')->index('user_id');
            $table->text('additional_info')->nullable();
            $table->double('total');
            $table->double('discount')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
