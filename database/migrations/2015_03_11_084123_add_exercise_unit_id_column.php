<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExerciseUnitIdColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('exercise_entries', function(Blueprint $table)
		{
			//
			$table->integer('exercise_unit_id')->unsigned(); //foreign key

			$table->foreign('exercise_unit_id')->references('id')->on('exercise_units');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('exercise_entries', function(Blueprint $table)
		{
			//
		});
	}

}
