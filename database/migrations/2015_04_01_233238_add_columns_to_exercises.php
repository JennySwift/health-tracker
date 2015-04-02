<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToExercises extends Migration {

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
			$table->decimal('step_number', 10, 2)->nullable();
			$table->integer('series_id')->nullable()->unsigned(); //foreign key

			$table->foreign('series_id')->references('id')->on('exercise_series');
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
		Schema::table('exercises', function(Blueprint $table)
		{
			//
		});
	}

}
