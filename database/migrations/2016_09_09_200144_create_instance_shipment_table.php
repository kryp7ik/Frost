<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstanceShipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instance_shipment', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('shipment_id')->unsigned()->index();
            $table->integer('product_instance_id')->unsigned()->index();
            $table->integer('quantity')->unsigned();

            $table->foreign('shipment_id')->references('id')->on('shipments')
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
        //
    }
}
