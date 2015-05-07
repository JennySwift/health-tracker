<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tags\Tag;
use Faker\Factory as Faker;

class TagSeeder extends Seeder {

	public function run()
	{
		Tag::truncate();
		
		$faker = Faker::create();

		foreach (range(1, 5) as $index) {
			Tag::create([
				'name' => $faker->word(),
				'user_id' => 1
			]);
		}
	}

}