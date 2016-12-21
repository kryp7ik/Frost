<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstanceTransferTable extends Migration
{
    /**
     * Run the migrations.
     * NOTE: added 'received' (bool) column in another migration
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instance_transfer', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('transfer_id')->unsigned()->index();
            $table->integer('product_instance_id')->unsigned()->index();
            $table->integer('quantity')->unsigned();

            $table->foreign('transfer_id')->references('id')->on('transfers')
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
