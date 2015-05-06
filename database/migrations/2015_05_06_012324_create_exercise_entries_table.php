<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExerciseEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$table->increments('id');
		$table->timestamps();
		$table->date('date');
		$table->integer('exercise_id')->unsigned(); //foreign key
		$table->integer('quantity');
		$table->integer('user_id')->unsigned(); //foreign key
		$table->integer('exercise_unit_id')->unsigned(); //foreign key

		$table->foreign('exercise_unit_id')->references('id')->on('exercise_units')->onDelete('cascade');
		$table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
		$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exercise_entries');
	}

}
