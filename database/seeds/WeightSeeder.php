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
		/**
		 * @VP:
		 * Why did you add the following line?
		 * I already have Model::unguard(); in DatabaseSeeder.php.
		 * How is Eloquent::unguard(); different?
		 */

		Eloquent::unguard();
		Weight::truncate();
		
		$faker = Faker::create();

		/**
		 * The goal:
		 * I want to create an entry in the weights table for each of the last 50 days.
		 */		
		
		foreach (range(0, 49) as $index) {
			/**
			 * @VP:
			 * This is creating 50 entries in the weights table like I want, 
			 * but not for the last 50 days-it is skipping days. Why?
			 */
			
			/**
			 * @JS:
			 * Simply create the $today variable inside the loop ;)
			 */
			 
			/**
			 * @VP:
			 * So it worked, but why?
			 * I thought $today would be the same value whether inside or outside the loop.
			 * $today->subDays($index)->format('Y-m-d') actually changes the value of the $today variable??
			 */
			
			/**
			 * @JS:
			 * By the way, no need for a $date variable here!
			 */
			
			/**
			 * @VP:
			 * I used to not create so many variables.
			 * But then I found it made debugging a lot easier when I did so,
			 * because it gives me a variable to look at in my debugger.
			 * Also, I think it makes the code easier to read.
			 * And I think it makes it easier to code, too,
			 * because it's like taking one step at a time rather than trying to do too many things at once.
			 * What do you think? Is there a good reason I should change this habit?
			 */
			
			/** 
			 * @JS:
			 * And also, if you take a look at my migrations, I never create a 'date' field
			 * Simply because you already have a created_at column which holds a date ;)
			 */
			
			/**
			 * @VP:
			 * I see, but I'd still like to understand the code here for the sake of learning.
			 */
			
			$today = Carbon::today();
			
			Weight::create([
				'date' => $today->subDays($index)->format('Y-m-d'),
				'weight' => $faker->randomFloat($nbMaxDecimals = 1, $min = 30, $max = 90),
				'user_id' => 1
			]);
		}		
		
	}

}