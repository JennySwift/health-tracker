<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesWorkoutPivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$table->increments('id');
		$table->timestamps();
		$table->integer('workout_id')->unsigned(); //foreign key
		$table->integer('series_id')->unsigned(); //foreign key
		$table->integer('user_id')->unsigned(); //foreign key

		$table->foreign('workout_id')->references('id')->on('workouts')->onDelete('cascade');
		$table->foreign('series_id')->references('id')->on('exercise_series')->onDelete('cascade');
		$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('series_workout_pivot');
	}

}
