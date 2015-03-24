<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExerciseTagTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::create('exercise_tag', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('exercise_id')->unsigned(); //foreign key
			$table->integer('tag_id')->unsigned(); //foreign key
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
			$table->foreign('tag_id')->references('id')->on('exercise_tags')->onDelete('cascade');
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

		Schema::drop('exercise_tag');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
