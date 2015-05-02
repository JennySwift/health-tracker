<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Calories;
use Faker\Factory as Faker;
//done
class CaloriesSeeder extends Seeder {

	public function run()
	{
		Calories::truncate();
		
		$faker = Faker::create();

		Calories::create([
			'food_id' => 1,
			'unit_id' => 1,
			'calories' => 50,
			'default_unit' => '',
			'user_id' => 1
		]);

		Calories::create([
			'food_id' => 2,
			'unit_id' => 2,
			'calories' => 10,
			'default_unit' => '',
			'user_id' => 1
		]);

		Calories::create([
			'food_id' => 3,
			'unit_id' => 3,
			'calories' => 100,
			'default_unit' => '',
			'user_id' => 1
		]);
	}

}