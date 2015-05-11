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

            /**
             * @JS:
             * You were on the right track with your loop, the issue was creating the today variable outside the
             * loop. If you create the today date outside the loop, let's say the first time you enter the loop
             * (first iteration) you have May 1st as your today date, index is 0 so your date will be May 1st. On the
             * second iteration, index will be 1 and today will be changed to April 30th. On the third iteration
             * though, index will be equal to 2, and the today variable after subDays() method will be equal to April
             * 28th. So you are skipping days, because your today date won't be equal to today on each iteration.
             * That is why we need to reset it to today's date inside the loop :)
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