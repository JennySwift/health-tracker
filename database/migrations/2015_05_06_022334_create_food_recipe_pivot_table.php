<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodRecipePivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('food_recipe', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('recipe_id')->unsigned(); //foreign key
			$table->integer('food_id')->unsigned(); //foreign key
			$table->integer('unit_id')->unsigned(); //foreign key
			$table->integer('user_id')->unsigned(); //foreign key
			$table->decimal('quantity', 10, 2);
			$table->string('description')->nullable();

			$table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
			$table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('food_recipe');
	}

}
