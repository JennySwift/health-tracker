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

		Schema::create('weight', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->date('date');
			$table->decimal('weight', 10, 2);
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::create('exercises', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->integer('user_id')->unsigned(); //foreign key
			$table->decimal('step_number', 10, 2)->nullable();
			$table->integer('series_id')->nullable()->unsigned(); //foreign key
			$table->decimal('default_quantity', 10, 2)->nullable();
			$table->string('description')->nullable();
			$table->integer('default_exercise_unit_id')->nullable()->unsigned(); //foreign key

			$table->foreign('default_exercise_unit_id')->references('id')->on('exercise_units');
			$table->foreign('series_id')->references('id')->on('exercise_series');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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

		Schema::create('foods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->unsigned(); //foreign key
			$table->string('name');

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::create('food_units', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::create('recipes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		//These are the more tables with more foreign keys

		Schema::create('calories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('food_id')->unsigned(); //foreign key
			$table->integer('unit_id')->unsigned(); //foreign key
			$table->decimal('calories', 10, 2)->nullable();
			$table->boolean('default_unit')->nullable();
			$table->integer('user_id')->unsigned(); //foreign key

			$table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
			$table->foreign('unit_id')->references('id')->on('food_units')->onDelete('cascade');	
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

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

		Schema::create('food_recipe', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('recipe_id')->unsigned(); //foreign key
			$table->integer('food_id')->unsigned(); //foreign key
			$table->integer('unit_id')->unsigned(); //foreign key
			$table->integer('user_id')->unsigned(); //foreign key
			$table->decimal('quantity', 10, 2);
			$table->string('description')->nullable();

			$table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
			$table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
			$table->foreign('unit_id')->references('id')->on('food_units')->onDelete('cascade');
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

		Schema::drop('weight');
		Schema::drop('exercise_entries');
		Schema::drop('exercises');
		Schema::drop('calories');
		Schema::drop('food_entries');
		Schema::drop('food_recipe');
		Schema::drop('foods');
		Schema::drop('food_units');
		Schema::drop('recipes');	
		Schema::drop('users');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
