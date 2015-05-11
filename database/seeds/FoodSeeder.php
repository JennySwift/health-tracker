<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Food;
use App\Models\Units\Unit;
use Faker\Factory as Faker;
use Laracasts\TestDummy\Factory;
// use Laracasts\TestDummy\DbTestCase;
use App\User;

class FoodSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Food::truncate();
		User::truncate();

		$foods = ['apple', 'banana', 'orange', 'mango', 'watermelon', 'papaya', 'pear', 'peach', 'nectarine', 'plum', 'rockmelon', 'blueberry', 'strawberry', 'raspberry', 'blackberry', 'walnut', 'brazilnut', 'cashew', 'almond', 'sesame seeds', 'pumpkin seeds', 'sunflower seeds'];

		foreach ($foods as $food) {
			Factory::create('App\Models\Foods\Food', ['name' => $food]);
		}
		
		// dd($food->toArray());

		$faker = Faker::create();

		/**
		 * $food_unit_ids should be only the units the food has
		 * in order to make sure the default unit is a unit the food has.
		 * The problem is the calories table is not yet seeded we don't yet know what units a food has.
		 * And the calories table needs the foods table to be seeded before it is seeded.
		 */
		
		// $food_unit_ids = Unit::where('for', 'food')->lists('id');

		
		
		/**
		 * Create foods but only add a default_unit_id to most of them, not all of them, to make it more realistic.
		 */

		// foreach ($foods as $food) {
		// 	// $has_default_unit_id = $faker->boolean($chanceOfGettingTrue = 80);

		// 	// if ($has_default_unit_id) {
		// 	// 	$default_unit_id = $faker->randomElement($food_unit_ids);
		// 	// }
		// 	// else {
		// 	// 	$default_unit_id = null;
		// 	// }

		// 	Food::create([
		// 		'name' => $food,
		// 		'user_id' => 1
		// 		// 'default_unit_id' => $default_unit_id
		// 	]);
		// }

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}