<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkoutsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::create('workouts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned(); //foreign key
			$table->string('name');
			$table->timestamps();
			
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		// DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::drop('workouts');

		// DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
