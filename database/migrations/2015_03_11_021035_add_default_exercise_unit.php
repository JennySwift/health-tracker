<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultExerciseUnit extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('exercises', function(Blueprint $table)
		{
			//
			$table->integer('default_exercise_unit_id')->nullable()->unsigned(); //foreign key

			$table->foreign('default_exercise_unit_id')->references('id')->on('exercise_units');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('exercises', function(Blueprint $table)
		{
			//
			// $table->dropColumn('exercise_unit_id');
		});
	}

}
