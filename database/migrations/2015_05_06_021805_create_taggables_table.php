<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('taggables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('exercise_id')->unsigned(); //foreign key
			$table->integer('tag_id')->unsigned(); //foreign key
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
			$table->foreign('tag_id')->references('id')->on('exercise_tags')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			
			// $table->increments('id');
			// $table->timestamps();
			// $table->integer('recipe_id')->unsigned(); //foreign key
			// $table->integer('tag_id')->unsigned(); //foreign key
			// $table->integer('user_id')->unsigned(); //foreign key

			// $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
			// $table->foreign('tag_id')->references('id')->on('recipe_tags')->onDelete('cascade');
			// $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('taggables');
	}

}
