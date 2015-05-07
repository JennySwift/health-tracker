<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Weights\Weight;
use Faker\Factory as Faker;
use Carbon\Carbon;

class WeightSeeder extends Seeder {

	public function run()
	{
		Weight::truncate();
		
		$faker = Faker::create();

		/**
		 * The goal:
		 * I want to create an entry in the weights table for each of the last 50 days.
		 */
		
		// $today = new DateTime('now');
		$today = Carbon::today();

		foreach (range(0, 49) as $index) {
			// $diff = new DateInterval('P' . $index . 'D');
			// $date = $today->sub($diff);

			/**
			 * @VP:
			 * This is creating 50 entries in the weights table like I want, but not for the last 50 days-it is skipping days. Why?
			 */

			$date = $today->subDays($index)->format('Y-m-d');

			Weight::create([
				'date' => $date,
				'weight' => $faker->randomFloat($nbMaxDecimals = 1, $min = 30, $max = 90),
				'user_id' => 1
			]);
		}
	}

}