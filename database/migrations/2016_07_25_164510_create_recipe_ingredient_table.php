<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeIngredientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_ingredient', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('recipe_id')->unsigned()->index();
            $table->integer('ingredient_id')->unsigned()->index();
            $table->integer('amount')->unsigned();
            $table->timestamps();

            $table->foreign('recipe_id')->references('id')->on('recipes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')
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
        Schema::drop('recipe_ingredient');
    }
}
