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
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::table('exercise_entries', function(Blueprint $table)
		{
			//
			$table->integer('exercise_unit_id')->unsigned(); //foreign key

			$table->foreign('exercise_unit_id')->references('id')->on('exercise_units');
		});

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::table('exercise_entries', function(Blueprint $table)
		{
			//
		});

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
