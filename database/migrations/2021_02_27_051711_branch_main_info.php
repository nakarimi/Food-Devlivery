<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BranchMainInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branche_main_info', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('business_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('logo');
            $table->string('banner');
            $table->string('contact')->nullable();
            $table->string('location')->nullable();
            $table->string('note')->nullable();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('branche_main_info');
    }
}
