<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customers');
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("gender");
            $table->integer("age");
            $table->string("phone")->nullable();
            $table->string("city")->nullable();
            $table->string("address")->nullable();
            $table->decimal("latitude", 8, 6)->nullable();
            $table->decimal("longitude", 8, 6)->nullable();
            $table->boolean("status")->default(true);
            $table->bigInteger('customer_id')->unsigned()->unique();
            // Due to using DB this field should have current datetime as default value.
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('customer_id')
            ->references('id')
            ->on('users')
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
        Schema::dropIfExists('customers');
    }
}
