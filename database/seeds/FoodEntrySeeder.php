<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Entry as FoodEntry;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\Foods\Food;
use App\Models\Units\Unit;

class FoodEntrySeeder extends Seeder {

	public function run()
	{
		FoodEntry::truncate();
		
		$faker = Faker::create();

		/**
		 * Objective: create a random number of food entries (no duplicates) for each of the last 50 days
		 */

		foreach (range(0, 50) as $days) {
			//Everything that happens in this loop is for one day
			$today = Carbon::today();
			$date = $today->subDays($days)->format('Y-m-d');
			$number = $faker->numberBetween($min = 1, $max = 6);		
			$food_ids = Food::lists('id')->all();

			foreach (range(0, $number) as $index) {
				$unit_ids = [];
				while (count($unit_ids) === 0) {
					$food_id = $faker->randomElement($food_ids);
					$food = Food::find($food_id);
					//So that the entry doesn't have a unit that doesn't belong to the food
					$unit_ids = $food->units()->lists('unit_id')->all();
				}

				DB::table('food_entries')->insert([
					'date' => $date,
					'food_id' => $food_id,
					'quantity' => $faker->numberBetween($min=1, $max=9),
					'unit_id' => $faker->randomElement($unit_ids),
					'recipe_id' => '',
					'user_id' => 1
				]);
			}
		}
	}

}