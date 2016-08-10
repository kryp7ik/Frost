<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiquidProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquid_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_order_id')->unsigned()->index();
            $table->integer('recipe_id')->unsigned();
            $table->integer('store')->unsigned();
            $table->integer('size');
            $table->decimal('nicotine');
            $table->integer('vg');
            $table->integer('menthol');
            $table->boolean('extra');
            $table->boolean('mixed');
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
        Schema::drop('liquid_products');
    }
}
