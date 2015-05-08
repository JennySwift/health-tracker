<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Weights\Weight;
use App\Models\Tags\Tag;
use App\Models\Tags\Taggable;
use Faker\Factory as Faker;
use Carbon\Carbon;

class WeightSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		Weight::truncate();
		
		$faker = Faker::create();

		/**
		 * The goal:
		 * I want to create an entry in the weights table for each of the last 50 days.
		 */
		
		// $today = new DateTime('now');
		

		foreach (range(0, 49) as $index) {
			// $diff = new DateInterval('P' . $index . 'D');
			// $date = $today->sub($diff);

			/**
			 * @VP:
			 * This is creating 50 entries in the weights table like I want, 
			 * but not for the last 50 days-it is skipping days. Why?
			 * Simply create the $today variable inside the loop ;)
			 * By the way, no need for a $date variable here!
			 * And also, if you take a look at my migrations, I never create a 'date' field
			 * Simply because you already have a created_at column which holds a date ;)
			 *
			 */
			$today = Carbon::today();
			// $date = $today->subDays($index)->format('Y-m-d');

			Weight::create([
				'date' => $today->subDays($index)->format('Y-m-d'),
				'weight' => $faker->randomFloat($nbMaxDecimals = 1, $min = 30, $max = 90),
				'user_id' => 1
			]);
		}		
		
	}

}