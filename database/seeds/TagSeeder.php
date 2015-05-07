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
			'user_id' => 1
		]);

		Tag::create([
			'name' => 'soup',
			'user_id' => 1
		]);

		Tag::create([
			'name' => 'pushups',
			'user_id' => 1
		]);

		Tag::create([
			'name' => 'pullups',
			'user_id' => 1
		]);
	}

}