<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->increments('id')->unsigned()->index();
            $table->integer('user_id')->unsigned();
            $table->integer('store')->unsigned();
            $table->string('start');
            $table->string('end');
            $table->string('in');
            $table->string('out');
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
