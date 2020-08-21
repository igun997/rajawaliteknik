<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ref_type');
            $table->integer('ref_id')->nullable()->index('ref_id');
            $table->double('total');
            $table->text('descriptions');
            $table->integer('user_id')->nullable()->index('user_id');
            $table->integer('type');
            $table->integer('status');
            $table->date('updated_at')->nullable();
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
