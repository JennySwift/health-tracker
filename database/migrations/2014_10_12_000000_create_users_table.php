<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->rememberToken();
			$table->timestamps();
		});

		Schema::create('exercise_entries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->date('date');
			$table->integer('exercise_id')->unsigned(); //foreign key
			$table->integer('quantity');
			$table->integer('user_id')->unsigned(); //foreign key
			$table->integer('exercise_unit_id')->unsigned(); //foreign key

			$table->foreign('exercise_unit_id')->references('id')->on('exercise_units')->onDelete('cascade');
			$table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		//These are the more tables with more foreign keys

		Schema::create('food_entries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->date('date');
			$table->integer('food_id')->unsigned(); //foreign key
			$table->decimal('quantity', 10, 2);
			$table->integer('unit_id')->unsigned(); //foreign key
			$table->integer('recipe_id')->unsigned()->nullable(); //foreign key
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
			$table->foreign('unit_id')->references('id')->on('food_units')->onDelete('cascade');
			$table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
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

		Schema::drop('exercise_entries');
		Schema::drop('food_entries');
		Schema::drop('foods');
		Schema::drop('users');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
