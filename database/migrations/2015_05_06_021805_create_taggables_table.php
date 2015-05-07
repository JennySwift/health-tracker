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
			$table->integer('tag_id')->unsigned()->index();
            $table->integer('taggable_id')->unsigned()->index();
            $table->string('taggable_type')->index();
            /**
             * My seeder errored when I didn't have timestamps:
             * Column not found: 1054 Unknown column 'updated_at' in 'field list'
             */
            $table->timestamps();

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
			//@VP: Shouldn't there be a foreign key for taggable_id here?
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
