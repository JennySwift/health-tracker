<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tags\Tag;
use Faker\Factory as Faker;

class TagSeeder extends Seeder {

	public function run()
	{
		Tag::truncate();
		
		Tag::create([
			'name' => 'main meal',
			'for' => 'food',
			'user_id' => 1
		]);

		Tag::create([
			'name' => 'soup',
			'for' => 'food',
			'user_id' => 1
		]);

		Tag::create([
			'name' => 'pushups',
			'for' => 'exercise',
			'user_id' => 1
		]);

		Tag::create([
			'name' => 'pullups',
			'for' => 'exercise',
			'user_id' => 1
		]);
	}

}