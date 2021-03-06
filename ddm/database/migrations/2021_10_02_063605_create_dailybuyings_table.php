<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailybuyingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dailybuyings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("shop_id")->nullable();
            $table->integer("brand_id")->nullable();
            $table->string('name');
            $table->string('qty')->nullable();
            $table->string('price')->nullable();
            $table->string('weight')->nullable();
            $table->string('frequency')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dailybuyings');
    }
}
