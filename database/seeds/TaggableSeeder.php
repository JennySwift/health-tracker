<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tags\Taggable;
use Faker\Factory as Faker;

/**
 * still to do
 */

class TaggableSeeder extends Seeder {

	public function run()
	{
		Taggable::truncate();

		Taggable::create([
			'tag_id' => 3,
			'taggable_id' => 1,
			'taggable_type' => 'exercise'
		]);

		Taggable::create([
			'tag_id' => 4,
			'taggable_id' => 1,
			'taggable_type' => 'exercise'
		]);

		Taggable::create([
			'tag_id' => 1,
			'taggable_id' => 2,
			'taggable_type' => 'recipe'
		]);

		Taggable::create([
			'tag_id' => 2,
			'taggable_id' => 2,
			'taggable_type' => 'recipe'
		]);
	}

}