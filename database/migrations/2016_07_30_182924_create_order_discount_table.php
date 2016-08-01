<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_discount', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('shop_order_id')->unsigned()->index();
            $table->integer('discount_id')->unsigned()->index();

            $table->foreign('shop_order_id')->references('id')->on('shop_orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')
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
        Schema::drop('order_discount');
    }
}
