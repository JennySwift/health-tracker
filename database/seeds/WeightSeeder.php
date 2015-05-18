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
		 * 
         * @JS:
         * Eloquent::unguard works if you have line 4: `use Illuminate\Database\Eloquent\Model as Eloquent;`
         * This is basically the same.
         * Now I am using it here, because you should try running your seeders alone (php artisan db:seed
         * --class=WhateverSeeder) to make sure they work independently. Seeders must be "decoupled", which means you
         * should be able to run each one of them separately if needed!
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
			
			$today = Carbon::today();
			
			Weight::create([
				'date' => $today->subDays($index)->format('Y-m-d'),
				'weight' => $faker->randomFloat($nbMaxDecimals = 1, $min = 30, $max = 90),
				'user_id' => 1
			]);
		}		
		
	}

}