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

		$today = new Carbon();

		foreach (range(1, 50) as $number) {
			// $date = Carbon::createFromFormat('Y-m-d', $today)->subDays($number)->format('Y-m-d');
			$date = Carbon::createFromFormat('Y-m-d', '2015-05-02')->subDays($number)->format('Y-m-d');

			/**
			 * @VP:
			 * Why is $food_id and $unit_id nothing here (below)?
			 * And when I replace the hard-coded date above with $today, I get an error:
			 *[InvalidArgumentException]  
  			 *Trailing data
			 */
			
			$food_ids = Food::lists('id');
			$food_id = $faker->randomElement($food_ids);

			$unit_ids = Unit::lists('id');
			$unit_id = $faker->randomElement($unit_ids);

			FoodEntry::create([
				'date' => $date,
				'food_id' => 1,
				'quantity' => $faker->numberBetween($min=1, $max=9),
				'unit_id' => 1,
				'recipe_id' => '',
				'user_id' => 1
			]);
		}
	}

}