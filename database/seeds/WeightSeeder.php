<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Weights\Weight;
use Faker\Factory as Faker;
//done but not sure if date will work
class WeightSeeder extends Seeder {

	public function run()
	{
		Weight::truncate();
		
		$faker = Faker::create();

		/**
		 * I want to create weight entries for the last 50 days.
		 * How would I use carbon for this?
		 */
		
		$today = new DateTime('now');
		foreach (range(1, 50) as $index) {
			$diff = new DateInterval('P' . $index . 'D');
			$date = $today->sub($diff);

			Weight::create([
				'date' => $date,
				'weight' => $faker->randomFloat($nbMaxDecimals = 1, $min = 30, $max = 90),
				'user_id' => 1
			]);
		}
	}

}