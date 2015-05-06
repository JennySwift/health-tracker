<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaloriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$table->increments('id');
		$table->timestamps();
		$table->integer('food_id')->unsigned(); //foreign key
		$table->integer('unit_id')->unsigned(); //foreign key
		$table->decimal('calories', 10, 2)->nullable();
		$table->boolean('default_unit')->nullable();
		$table->integer('user_id')->unsigned(); //foreign key

		$table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
		$table->foreign('unit_id')->references('id')->on('food_units')->onDelete('cascade');	
		$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
