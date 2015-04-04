<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesWorkoutTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::create('series_workout', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('workout_id')->unsigned(); //foreign key
			$table->integer('series_id')->unsigned(); //foreign key
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('workout_id')->references('id')->on('workouts')->onDelete('cascade');
			$table->foreign('series_id')->references('id')->on('exercise_series')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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

		Schema::drop('series_workout');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
