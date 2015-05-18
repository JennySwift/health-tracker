<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class TaggableSeeder extends Seeder {

	public function run()
	{
		DB::table('taggables')->truncate();

		DB::table('taggables')->insert([
			'tag_id' => 3,
			'taggable_id' => 1,
			'taggable_type' => 'exercise'
		]);

		DB::table('taggables')->insert([
			'tag_id' => 4,
			'taggable_id' => 1,
			'taggable_type' => 'exercise'
		]);

		DB::table('taggables')->insert([
			'tag_id' => 1,
			'taggable_id' => 2,
			'taggable_type' => 'recipe'
		]);

		DB::table('taggables')->insert([
			'tag_id' => 2,
			'taggable_id' => 2,
			'taggable_type' => 'recipe'
		]);
	}

}