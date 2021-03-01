<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('item_id')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('code')->nullable();
            $table->string('thumbnail')->nullable();
            $table->double('price')->nullable();
            $table->double('package_price')->nullable();
            $table->string('unit')->nullable();
            $table->string('details_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_details');
    }
}
