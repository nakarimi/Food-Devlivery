<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivedDriverPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_driver_payments', function (Blueprint $table) {
            $table->id();
            $table->string('orders_id'); // The orders id should be stored as the serialize array 
            $table->decimal('total_money_received');
            $table->unsignedInteger('driver_id')->index();
            $table->foreignId('finance_manager_id')->index();

            // Due to using DB this field should have current datetime as default value.
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('finance_manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');    
            $table->foreign('driver_id')
                ->references('id')
                ->on('drivers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('received_driver_payments');
    }
}
