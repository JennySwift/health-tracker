<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Food;
use App\Models\Units\Unit;
use Faker\Factory as Faker;
use Laracasts\TestDummy\Factory as Dummy;
// use Laracasts\TestDummy\DbTestCase;
use App\User;

class FoodSeeder extends Seeder {

	public function run()
	{
		// DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Food::truncate();

		/**
		 * Objective:
		 * Give one user all foods in $user_one_foods.
		 * Give second user all foods in $user_two_foods.
		 * Add a default_unit_id to most foods, but not all of them, to make it more realistic.
		 * The default_unit_id must be a unit id that belongs to the food (in food_units table).
		 * The problem is the food_units table is not yet seeded, so we don't yet know what units belong to a food.
		 * But the food_units table needs the foods table to be seeded before it is seeded.
		 */

		$user_one_foods = ['apple', 'banana', 'orange', 'mango', 'watermelon', 'papaya', 'pear', 'peach', 'nectarine', 'plum', 'rockmelon', 'blueberry', 'strawberry', 'raspberry', 'blackberry', 'walnut', 'brazilnut', 'cashew', 'almond', 'sesame seeds', 'pumpkin seeds', 'sunflower seeds'];
		$user_two_foods = ['quinoa', 'rice', 'buckwheat'];

		foreach ($user_one_foods as $food) {
			Dummy::create('App\Models\Foods\Food', [
				'name' => $food,
				'user_id' => 1
			]);
		}

		$faker = Faker::create();
		
		// $food_unit_ids = Unit::where('for', 'food')->lists('id');

		// foreach ($foods as $food) {
			// $has_default_unit_id = $faker->boolean($chanceOfGettingTrue = 80);

			// if ($has_default_unit_id) {
			// 	$default_unit_id = $faker->randomElement($food_unit_ids);
			// }
			// else {
			// 	$default_unit_id = null;
			// }

			// Food::create([
				// 'name' => $food,
				// 'user_id' => 1
				// 'default_unit_id' => $default_unit_id
			// ]);
		// }

		// DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}