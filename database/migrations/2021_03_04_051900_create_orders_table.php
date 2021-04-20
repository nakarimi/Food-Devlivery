<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('title')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('payment_id')->nullable();
            $table->tinyInteger('has_delivery')->nullable();
            $table->string('total')->nullable();
            $table->double('commission_value')->nullable();
            $table->string('status')->nullable();
            $table->string('note')->nullable();
            $table->string('reciever_phone')->nullable();
            $table->json('contents')->nullable();
            $table->tinyInteger('paid')->default(0);  // This column will change to 1 when restaurant do the payment and calculation for this be done by finance manager.
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
