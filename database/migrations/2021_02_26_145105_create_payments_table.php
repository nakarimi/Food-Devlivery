<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('branch_id')->nullable();
            $table->integer('reciever_id')->nullable();
            $table->integer('total_order');
            $table->double('total_order_income');
            $table->double('total_general_commission');
            $table->double('total_delivery_commission');
            $table->date('range_from');
            $table->date('range_to');
            $table->string('note')->nullable();
            $table->string('status')->default('pending');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payments');
    }
}
