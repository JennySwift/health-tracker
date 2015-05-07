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
		
		$faker = Faker::create();

		foreach (range(1, 5) as $index) {
			Tag::create([
				'tag_id' => '',
				'taggable_id' => '',
				'taggable_type' => ''
			]);
		}
	}

}