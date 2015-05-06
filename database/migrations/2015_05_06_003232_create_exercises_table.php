<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExercisesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$table->increments('id');
		$table->timestamps();
		$table->string('name');
		$table->integer('user_id')->unsigned(); //foreign key
		$table->decimal('step_number', 10, 2)->nullable();
		$table->integer('series_id')->nullable()->unsigned(); //foreign key
		$table->decimal('default_quantity', 10, 2)->nullable();
		$table->string('description')->nullable();
		$table->integer('default_exercise_unit_id')->nullable()->unsigned(); //foreign key

		$table->foreign('default_exercise_unit_id')->references('id')->on('exercise_units');
		$table->foreign('series_id')->references('id')->on('exercise_series');
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
