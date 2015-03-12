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
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::table('exercises', function(Blueprint $table)
		{
			//
			$table->integer('default_exercise_unit_id')->nullable()->unsigned(); //foreign key

			$table->foreign('default_exercise_unit_id')->references('id')->on('exercise_units');
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

		Schema::table('exercises', function(Blueprint $table)
		{
			//
			// $table->dropColumn('exercise_unit_id');
		});

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
