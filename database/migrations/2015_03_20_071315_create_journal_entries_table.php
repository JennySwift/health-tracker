<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Schema::create('journal_entries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->date('date');
			$table->text('text');
			$table->integer('user_id')->unsigned(); //foreign key

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

		Schema::drop('journal_entries');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}
