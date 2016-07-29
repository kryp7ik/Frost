<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_instances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->decimal('price');
            $table->integer('stock');
            $table->integer('redline');
            $table->boolean('active');
            $table->integer('store');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_instances');
    }
}
