<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Food;
use App\Models\Units\Unit;
use Faker\Factory as Faker;

/**
 * @VP:
 * Why do I get this error if I try to use the following line?
 * [ErrorException]
 * The use statement with non-compound name 'DB' has no effect
 */
// use DB;

class FoodUnitSeeder extends Seeder {

	public function run()
	{
		DB::table('food_unit')->truncate();
		
		$faker = Faker::create();

		/**
		 * Objective:
		 * Add a random number of rows to the food_units table for each food
		 * so that each food has many units (no duplicates).
		 * Set the calories for some, but not all, of the rows.
		 *
		 * Also, for a food's default unit, is it better to have a 'default_unit_id' column in my foods table (with a foreign key),
		 * or to have a 'default' column in my food_units table (with a boolean value)?
		 */

		$food_ids = Food::lists('id');
		$food_unit_ids = Unit::where('for', 'food')->lists('id');
		
		/**
		 * This code isn't working properly. I guess I'll wait till the TestDummy is working.
		 */

		foreach ($food_ids as $food_id) {
			$food_has_default_unit = false;

			//Add a few units for each food
			foreach ($food_unit_ids as $unit_id) {
				//Decide if this food should have this unit
				$food_has_unit = $faker->boolean($chanceOfGettingTrue = 50);
				
				if ($food_has_unit) {
					//Decide if this food with this unit should have calories set
					$has_calories = $faker->boolean($chanceOfGettingTrue = 80);

					if ($has_calories) {
						$calories = $faker->numberBetween($min = 30, $max = 150);
					}
					else {
						$calories = null;
					}

					DB::table('food_unit')->insert([
						'food_id' => $food_id,
						'unit_id' => $unit_id,
						'calories' => $calories,
						'user_id' => 1
					]);	

					// //Decide if this unit should be the food's default unit
					// //but only if the food does not yet have its default unit set
					// if ($food_has_default_unit) {
					// 	Calories::create([
					// 		'food_id' => $food_id,
					// 		'unit_id' => $unit_id,
					// 		'calories' => $calories,
					// 		'user_id' => 1
					// 	]);	
					// }
					// else {
					// 	//Food does not have a default unit yet
					// 	$is_default_unit = $faker->boolean($chanceOfGettingTrue = 30);

					// 	if ($is_default_unit) {
					// 		$food_has_default_unit = true;
					// 		//Food now has a default unit. Insert it into the foods table.
					// 		Food
					// 			::where('id', $food_id)
					// 			->update([
					// 				'default_unit_id' => $unit_id
					// 			]);
					// 	}
					// }

				}
			}
		}
	}

}