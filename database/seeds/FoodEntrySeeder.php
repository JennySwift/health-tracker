<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\FoodEntry;
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
		 * create a food entry for the last 50 days
		 */

		foreach (range(0, 49) as $index) {
			$today = Carbon::today();
			
			$food_ids = Food::lists('id');
			$unit_ids = Unit::lists('id');

			FoodEntry::create([
				'date' => $today->subDays($index)->format('Y-m-d'),
				'food_id' => $faker->randomElement($food_ids),
				'quantity' => $faker->numberBetween($min=1, $max=9),
				'unit_id' => $faker->randomElement($unit_ids),
				'recipe_id' => '',
				'user_id' => 1
			]);
		}
	}

}