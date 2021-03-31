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
            $table->double('paid_amount')->nullable();
            $table->date('date_and_time')->nullable();
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
