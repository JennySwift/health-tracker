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
		Schema::create('journal_entries', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('user_id')->unsigned()->index();
			$table->date('date')->index();
			$table->text('text');
			$table->timestamps();
			
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			/**
			 * @VP:
			 * When I tried adding ->index() to my 'text' column, and ran my migrations and seeders, I got the following error:
			 * [Illuminate\Database\QueryException]
  			 * SQLSTATE[42000]: Syntax error or access violation: 1170 BLOB/TEXT column 'text' used in key spec
  			 * ification without a key length (SQL: alter table `journal_entries` add index journal_entries_tex
  			 * t_index(`text`))
			 */
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('journal_entries');
	}

}
