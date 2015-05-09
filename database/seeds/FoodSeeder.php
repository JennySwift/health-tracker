<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Food;
use App\Models\Units\Unit;
use Faker\Factory as Faker;
use Laracasts\TestDummy\Factory;

class FoodSeeder extends Seeder {

	public function run()
	{
		Food::truncate();

		// Factory::build('App\Models\Foods\Food');

		$faker = Faker::create();

		$food_unit_ids = Unit::where('for', 'food')->lists('id');

		$foods = ['apple', 'banana', 'orange', 'mango', 'watermelon', 'papaya', 'pear', 'peach', 'nectarine', 'plum', 'rockmelon', 'blueberry', 'strawberry', 'raspberry', 'blackberry', 'walnut', 'brazilnut', 'cashew', 'almond', 'sesame seeds', 'pumpkin seeds', 'sunflower seeds'];
		
		/**
		 * Create foods but only add a default_unit_id to most of them, not all of them, to make it more realistic.
		 */

		foreach ($foods as $food) {

			$has_default_unit_id = $faker->boolean($chanceOfGettingTrue = 80);

			if ($has_default_unit_id) {
				$default_unit_id = $faker->randomElement($food_unit_ids);
			}
			else {
				$default_unit_id = null;
			}

			Food::create([
				'name' => $food,
				'user_id' => 1,
				'default_unit_id' => $default_unit_id
			]);
		}
	}

}