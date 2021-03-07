<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderTimeSlots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_timing', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('order_id');
            $table->timestamp('approved_time')->nullable();
            $table->timestamp('rejected_time')->nullable();
            $table->timestamp('processing_time')->nullable();
            $table->timestamp('caceled_time')->nullable();
            $table->timestamp('delivery_time')->nullable();
            $table->timestamp('completed_time')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_timing');
    }
}