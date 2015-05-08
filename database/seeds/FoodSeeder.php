<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Food;
use Faker\Factory as Faker;
use Laracasts\TestDummy\Factory;

class FoodSeeder extends Seeder {

	public function run()
	{
		Food::truncate();

		Factory::build('App\Models\Foods\Food');

		// $faker = Faker::create();
		
		// Food::create([
		// 	'name' => 'apple',
		// 	'user_id' => 1,
		// 	'default_unit_id' => $faker->numberBetween($min = 3, $max = 6)
		// ]);

		// Food::create([
		// 	'name' => 'orange',
		// 	'user_id' => 1,
		// 	'default_unit_id' => $faker->numberBetween($min = 3, $max = 6)
		// ]);

		// Food::create([
		// 	'name' => 'banana',
		// 	'user_id' => 1,
		// 	'default_unit_id' => $faker->numberBetween($min = 3, $max = 6)
		// ]);
	}

}