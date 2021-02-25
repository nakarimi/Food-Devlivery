<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->nullable();
            $table->string('business_type')->nullable();
            $table->integer('main_commission_id')->nullable();
            $table->integer('deliver_commission_id')->nullable();
            $table->string('status')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('contact')->nullable();
            $table->string('location')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('branches');
    }
}
