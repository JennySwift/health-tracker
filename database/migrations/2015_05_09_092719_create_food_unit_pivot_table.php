<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodUnitPivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('food_unit', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->index();
			$table->integer('food_id')->unsigned()->index();
			$table->integer('unit_id')->unsigned()->index();
			$table->decimal('calories', 10, 2)->nullable();

			$table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');	
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
		Schema::drop('food_unit');
	}

}
