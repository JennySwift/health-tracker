<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeMethodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::create('recipe_methods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('recipe_id')->unsigned(); //foreign key
			$table->integer('step');
			$table->string('text');
			$table->integer('user_id')->unsigned(); //foreign key

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

		Schema::drop('recipe_methods');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
