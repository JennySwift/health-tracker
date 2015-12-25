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
		Schema::create('exercises', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('user_id')->unsigned()->index();
			$table->integer('program_id')->unsigned()->index();
			$table->foreign('program_id')->references('id')->on('exercise_programs');
			$table->string('name')->index();
			$table->decimal('step_number', 10, 2)->nullable();
			$table->integer('series_id')->nullable()->unsigned();
			$table->decimal('default_quantity', 10, 2)->nullable();
			$table->string('description')->nullable();
			$table->integer('default_unit_id')->nullable()->unsigned();
			$table->timestamps();

			$table->foreign('default_unit_id')->references('id')->on('units');
			$table->foreign('series_id')->references('id')->on('exercise_series');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exercises');
	}

}
