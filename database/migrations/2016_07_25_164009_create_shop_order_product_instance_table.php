<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopOrderProductInstanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('shop_order_id')->unsigned()->index();
            $table->integer('product_instance_id')->unsigned()->index();
            $table->integer('quantity')->unsigned();

            $table->foreign('shop_order_id')->references('id')->on('shop_orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('product_instance_id')->references('id')->on('product_instances')
                ->onUpdate('cascade')
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
        Schema::drop('order_product');
    }
}
