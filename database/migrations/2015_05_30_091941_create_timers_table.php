<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('payee_id')->unsigned()->index();
			$table->integer('payer_id')->unsigned()->nullable()->index();
			$table->decimal('rate_per_hour', 10, 2)->nullable();
			$table->boolean('paid')->nullable()->index();
			$table->timestamps();

			$table->foreign('payer_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('payee_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('timers');
	}

}
